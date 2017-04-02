<?php namespace App\Http\Controllers;

use App\Events\NewEpisodeEvent;
use App\Models\Kinox\Episode;
use App\Models\Kinox\Mirror;
use App\Models\Kinox\Movie;
use App\Models\Kinox\Series;
use App\Models\MediaResult;
use App\Models\SearchResult;
use App\Models\Series as JsonSeries;
use App\Models\Season as JsonSeason;
use App\Models\Episode as JsonEpisode;
use App\Models\Mirror as JsonMirror;
use App\Models\Movie as JsonMovie;
use App\Services\ParserService as Parser;
use Curl;
use Event;

class KinoxController extends Controller
{
    const PROVIDER = "kinox";
    const URL_BASE = "https://kinox.to";
    const URL_SEARCH = self::URL_BASE . "/Search.html?q=%s";
    const URL_MEDIA = self::URL_BASE . "/Stream/%s.html";
    const URL_EPISODE_MIRRORS = self::URL_BASE . "/aGET/MirrorByEpisode/?Addr=%s&Season=%s&Episode=%s";
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

        $results = [];
        foreach ($parser->getItems("//*[@id='RsltTableStatic']//tbody[1]//tr") as $tr) {
            $img = $parser->getAttribute(".//td[1]//img[1]", 'src', $tr);
            $link = $parser->getAttribute(".//td[3]//a[1]", 'href', $tr);
            $type = $parser->getAttribute(".//td[2]//img[1]", 'title', $tr);

            $result = new SearchResult(self::PROVIDER, $type, $this->getStreamId($link), $parser->getText(".//td[3]//a[1]", $tr));
            $result->language = $this->getLanguage($img);

            if (starts_with($link, '/'))
                array_push($results, $result);
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

        $retMovie = new JsonMovie(self::PROVIDER, $movie->id);
        $retMovie->name = $movie->name;
        foreach(Mirror::getBy($id, 0, 0) as $mirror)
        {
            $retMirror = new JsonMirror($mirror->hoster_id, $mirror->hoster);
            if (array_key_exists($mirror->hoster, self::HOSTERS)) {
                $hoster = self::HOSTERS[$mirror->hoster];
                $retMirror->proxy = $hoster['proxy'];
                $retMirror->mp4 = $hoster['mp4'];
                if ($hoster['wait'] > 0)
                    $retMirror->wait = $hoster['wait'];
            }
            $retMovie->AddMirror($retMirror);
        }

        return response()->json($retMovie);
    }

    public function Series(Parser $parser, $id)
    {
        $series = Series::find($id);

        if ($series == null || $series->updateDue()) {
            $this->parseSeries($parser, $id);
            $series = Series::find($id);
        }

        $retSeries = new JsonSeries(self::PROVIDER, $series->id);
        $retSeries->name = $series->name;
        foreach ($series->episodes as $episode)
        {
            if (!$retSeries->ContainsSeason($episode->season)) {
                $retSeason = new JsonSeason($episode->season);
                $retSeries->AddSeason($retSeason);
            }
        }

        return response()->json($retSeries);
    }

    public function Season(Parser $parser, $id, $season)
    {
        $series = Series::find($id);

        if ($series == null || $series->updateDue()) {
            $this->parseSeries($parser, $id);
            $series = Series::find($id);
        }

        $retSeason = new JsonSeason($season);
        foreach ($series->episodes()->where('season', $season)->get() as $episode)
        {
            $retEpisode = new JsonEpisode($episode->episode);
            $retSeason->AddEpisode($retEpisode);
        }

        return response()->json($retSeason);
    }

    public function Episode(Parser $parser, $id, $season, $episode)
    {
        $series = Series::find($id);

        if ($series == null || $series->updateDue()) {
            $this->parseSeries($parser, $id);
            $series = Series::find($id);
        }

        $this->parseMirrors($parser, $id, $season, $episode);

        $retEpisode = new JsonEpisode($episode);
        foreach(Mirror::getBy($id, $season, $episode) as $mirror)
        {
            $retMirror = new JsonMirror($mirror->hoster_id, $mirror->hoster);
            if (array_key_exists($mirror->hoster, self::HOSTERS)) {
                $hoster = self::HOSTERS[$mirror->hoster];
                $retMirror->proxy = $hoster['proxy'];
                $retMirror->mp4 = $hoster['mp4'];
                if ($hoster['wait'] > 0)
                    $retMirror->wait = $hoster['wait'];
            }
            $retEpisode->AddMirror($retMirror);
        }

        return response()->json($retEpisode);
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

    private function parseMirrors(Parser $parser, $id, $season, $episode)
    {
        $page = Curl::to(sprintf(self::URL_EPISODE_MIRRORS, $id, $season, $episode))->get();
        $parser = $parser->from($page);

        foreach ($parser->getItems("//li") as $li) {
            Mirror::updateOrCreate(['media_id' => $id, 'season' => $season, 'episode' => $episode, 'hoster_id' => intval($this->getHoster($li->getAttribute('id'))), 'hoster' => $parser->getText(".//div[1]", $li)],
                ['count' => $this->getMirrorCount($parser->getText(".//div[2]", $li))]);
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