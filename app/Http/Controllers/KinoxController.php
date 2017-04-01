<?php namespace App\Http\Controllers;

use App\Events\NewEpisodeEvent;
use App\Models\Kinox\Episode;
use App\Models\Kinox\Mirror;
use App\Models\Kinox\Movie;
use App\Models\Kinox\Series;
use App\Models\MediaResult;
use App\Services\ParserService as Parser;
use Curl;
use Event;

class KinoxController extends Controller
{
    const PROVIDER = "kinox";
    const URL_BASE = "https://kinox.to";
    const URL_SEARCH = self::URL_BASE . "/Search.html?q=%s";
    const URL_MEDIA = self::URL_BASE . "/Stream/%s.html";
    const HOSTERS = [
        'StreamCloud.eu' => [
            'class' => 'App\Services\Hosters\Streamcloud',
            'mp4' => true,
            'proxy' => true,
            'wait' => 0
        ],
        'Shared.sx' => [
            'class' => 'App\Services\Hosters\Shared',
            'mp4' => true,
            'proxy' => true,
            'wait' => 0
        ]
    ];

    public function Search(Parser $parser, $search)
    {
        $page = Curl::to(sprintf(self::URL_SEARCH, $search))->get();
        $parser = $parser->from($page);

        $results = array();
        foreach ($parser->getItems("//*[@id='RsltTableStatic']//tbody[1]//tr") as $tr) {
            $img = $parser->getAttribute(".//td[1]//img[1]", 'src', $tr);
            $link = $parser->getAttribute(".//td[3]//a[1]", 'href', $tr);
            $type = $parser->getAttribute(".//td[2]//img[1]", 'title', $tr);

            $result = new MediaResult(self::PROVIDER, $type);
            $result->id = $this->getStreamId($link);
            $result->name = $parser->getText(".//td[3]//a[1]", $tr);
            $result->language = $this->getLanguage($img);

            if (starts_with($link, '/')) {
                array_push($results, $result);
            }
        }

        return $results;
    }

    public function Movie(Parser $parser, $id)
    {
        $movie = Movie::find($id);

        if ($movie == null || $movie->updateDue()) {
            $this->parseMovie($parser, $id);
            $movie = Movie::find($id);
        }

        $data = new MediaResult(self::PROVIDER, 'movie');
        $data->id = $movie->id;
        $data->name = $movie->name;
        $data->mirrors = [];
        foreach(Mirror::getBy($id, 0, 0) as $mirror)
        {
            $data->mirrors[] = new \stdClass();
            $dataMirror = &$data->mirrors[count($data->mirrors) - 1];
            $dataMirror->name = $mirror->hoster;
            $dataMirror->id = $mirror->hoster_id;
            $dataMirror->mp4 = false;
            $dataMirror->proxy = false;
            if (array_key_exists($mirror->hoster, self::HOSTERS)) {
                $hoster = self::HOSTERS[$mirror->hoster];
                $dataMirror->proxy = $hoster['proxy'];
                $dataMirror->mp4 = $hoster['mp4'];
                if ($hoster['wait'] > 0)
                    $dataMirror->wait = $hoster['wait'];
            }
        }

        return response()->json($data);
    }

    public function Series(Parser $parser, $id)
    {
        $series = Series::find($id);

        if ($series == null || $series->updateDue()) {
            $this->parseSeries($parser, $id);
            $series = Series::find($id);
        }

        $data = new MediaResult(self::PROVIDER, 'series');
        $data->id = $series->id;
        $data->name = $series->name;
        $data->seasons = [];
        foreach ($series->episodes as $episode) {
            if (!array_key_exists($episode->season, $data->seasons)) {
                $data->seasons[$episode->season] = new \stdClass();
                $data->seasons[$episode->season]->language = $series->language;
                $data->seasons[$episode->season]->episodes = [];
            }
            $data->seasons[$episode->season]->episodes[$episode->episode] = new \stdClass();
            $data->seasons[$episode->season]->episodes[$episode->episode]->name = 'Episode ' . $episode->episode;
            $data->seasons[$episode->season]->episodes[$episode->episode]->language = null;
        }

        return response()->json($data);
    }

    private function parseMovie(Parser $parser, $id)
    {
        $movie = Movie::firstOrNew(['id' => $id]);

        $page = Curl::to(sprintf(self::URL_MEDIA, $id))->get();
        $parser = $parser->from($page);

        $movie->id = $id;
        $movie->name = $parser->getText("//*[@id='Vadda']//h1//span");
        $movie->language = $this->getLanguage($parser->getAttribute("//*[@id='Vadda']//img[@alt='language']", "src"));
        $movie->touch();
        $movie->save();

        $hosterList = $parser->getItems("//*[@id='HosterList']//li");
        foreach ($hosterList as $li) {
            Mirror::updateOrCreate(['media_id' => $id, 'season' => 0, 'episode' => 0, 'hoster_id' => intval($this->getHoster($li->getAttribute('id'))), 'hoster' => $parser->getText(".//div[1]", $li)],
                ['count' => $this->getMirrorCount($parser->getText(".//div[2]", $li))]);
        }
    }

    public function parseSeries(Parser $parser, $id)
    {
        $series = Series::firstOrNew(['id' => $id]);

        $page = Curl::to(sprintf(self::URL_MEDIA, $id))->get();
        $parser = $parser->from($page);

        $series->id = $id;
        $series->name = $parser->getText("//*[@id='Vadda']//h1//span");
        $series->language = $this->getLanguage($parser->getAttribute("//*[@id='Vadda']//img[@alt='language']", "src"));
        $series->touch();
        $series->save();

        $select = $parser->getItems("//*[@id='SeasonSelection']//option");
        foreach ($select as $option) {
            $season = $option->getAttribute('value');
            foreach (explode(',', $option->getAttribute('rel')) as $episode) {
                $episode = Episode::firstOrNew(['series_id' => $id, 'season' => $season, 'episode' => $episode]);
                if ($episode->isDirty())
                {
                    $episode->save();
                    if ($series->created_at != $series->updated_at) {
                        // Series was updated
                        Event::fire(NewEpisodeEvent::Kinox($series, $episode));
                    }
                }
            }
        }
    }

    private function getHoster($url)
    {
        return str_replace('Hoster_', '', $url);
    }

    private function getMirrorCount($url)
    {
        return explode('V', explode('/', $url)[1])[0];
    }

    private function getStreamId($url)
    {
        return preg_replace('/\/Stream\/([^.]+)\.html/Ui', '$1', $url);
    }

    private function getLanguage($url)
    {
        $language = preg_replace('#/gr/sys/lng/(\d+).png#Ui', '$1', $url);
        switch ($language)
        {
            case "1": return "de";
            case "2": return "en";
            case "4": return "ru";
            case "5": return "es";
            case "6": return "fr";
            case "8": return "ja";
            case "15": return "en/de";
            case "17": return "ko";
            default: return "unkown";
        }
    }
}