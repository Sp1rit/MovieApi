<?php namespace App\Http\Controllers;

use App\Models\MediaResult;
use App\Models\Movie4k\Mirror;
use App\Models\Movie4k\Movie;
use App\Services\ParserService as Parser;
use Curl;

class Movie4kController extends Controller
{
    const PROVIDER = "movie4k";
    const URL_BASE = "https://movie4k.tv";
    const URL_SEARCH = self::URL_BASE . "/movies.php?list=search&search=%s";
    const URL_MEDIA = self::URL_BASE . "/%s.html";
    const HOSTERS = [
            'Streamclou' => [
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
        if (str_contains($page, 'DDoS protection by Cloudflare')) {
            return response('', 503);
        }
        $parser = $parser->from($page);

        $results = array();
        foreach ($parser->getItems("//*[@id='tablemoviesindex']//tr") as $tr) {
            $img = $parser->getAttribute(".//td[5]//img[1]", 'src', $tr);
            $link = $parser->getAttribute(".//td[1]//a[1]", 'href', $tr);
            $name = trim($parser->getText(".//td[1]//a[1]", $tr));

            $result = new MediaResult(self::PROVIDER, $this->getType($name));
            $result->id = $this->getStreamId($link);
            $result->name = $name;
            $result->language = $this->getLanguage($img);

            if (ends_with($link, '.html') && $result->type == 'movie') {
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
            $dataMirror->id = $mirror->hoster;
            $dataMirror->quality = $mirror->quality;
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

    private function parseMovie(Parser $parser, $id)
    {
        $movie = Movie::firstOrNew(['id' => $id]);

        $page = Curl::to(sprintf(self::URL_MEDIA, $id))->get();
        $parser = $parser->from($page);

        $movie->id = $id;
        $movie->name = trim($parser->getText("//*[@id='maincontent5']//div[1]//div[2]//span[1]//h1[1]//a[1]"));
        $movie->language = $this->getLanguage($parser->getAttribute("//*[@id='maincontent5']//div[1]//div[2]//span[1]//img[1]", "src"));
        $movie->touch();
        $movie->save();

        preg_match_all("/<a href=\\\\\"([^\\\\]*)\\\\\">([0-9.]*).*&nbsp;([^<]*).*\\/img\\/smileys\\/([0-9])/", $page, $jsMatches, PREG_SET_ORDER);
        preg_match_all('/<a href=\\\\?\"([^\\\\\"]*)\\\\?\">([0-9.]{10})[^&]*&nbsp;([a-zA-Z.]*)<\\/a>.*\\/img\\/smileys\\/([0-9])\\.gif/', $page, $htmlMatches, PREG_SET_ORDER);

        $matches = array_merge($jsMatches, $htmlMatches);
        foreach($matches as $match) {
            Mirror::updateOrCreate(['media_id' => $id, 'season' => 0, 'episode' => 0, 'hoster_id' => $this->getStreamId(trim($match[1])), 'hoster' => trim($match[3])],
                ['quality' => $match[4]]);
        }
    }

    private function getStreamId($url)
    {
        return preg_replace('/([^.]+)\.html/Ui', '$1', $url);
    }

    private function getLanguage($url)
    {
        $language = preg_replace('#/img/([a-z_]+).(png|gif)#Ui', '$1', $url);
        switch($language)
        {
            case 'us_ger_small': return 'de';
            case 'us_flag_small': return 'en';
            case 'flag_spain': return 'es';
            case 'flag_france': return 'fr';
            case 'flag_italy': return 'it';
            case 'flag_greece': return 'el';
            case 'flag_russia': return 'ru';
            case 'flag_turkey': return 'tr';
            default: return 'unkown';
        }
    }

    private function getType($name)
    {
        return ends_with($name, "(Serie)") || ends_with($name, "(TVshow)") ? 'series' : 'movie';
    }
}
