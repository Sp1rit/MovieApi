<?php namespace App\Http\Controllers;

use App\Models\MediaResult;
use App\Services\ParserService as Parser;
use Curl;

class Movie4kController extends Controller
{
    const PROVIDER = "movie4k";
    const URL_BASE = "https://movie4k.tv";
    const URL_SEARCH = self::URL_BASE . "/movies.php?list=search&search=%s";

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
            default: 'unkown';
        }
    }

    private function getType($name)
    {
        return ends_with($name, "(Serie)") || ends_with($name, "(TVshow)") ? 'series' : 'movie';
    }
}
