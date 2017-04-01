<?php namespace App\Http\Controllers;

use App\Models\MediaResult;
use App\Services\ParserService as Parser;
use Curl;

class KinoxController extends Controller
{
    const PROVIDER = "kinox";
    const URL_BASE = "https://kinox.to";
    const URL_SEARCH = self::URL_BASE . "/Search.html?q=%s";
    const URL_MEDIA = self::URL_BASE . "/Stream/%s.html";

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

    public function Series(Parser $parser, $id)
    {
        //$series = KinoxSeries::find($id);

        //if ($series == null || $series->updateDue()) {
            $this->parseSeries($parser, $id);
            $series = KinoxSeries::find($id);
        //}

        $data = new MediaResult(self::PROVIDER, 'series');
        $data->id = $series->id;
        $data->name = $series->name;
        $data->seasons = [];
        foreach ($series->episodes as $episode) {
            if (!array_key_exists($episode->season, $data->seasons)) {
                $data->seasons[$episode->season] = new \stdClass();
                $data->seasons[$episode->season]->language = $series->language;
                $data->seasons[$episode->season]->unseen = $series->unseen($episode->season);
            }
        }

        return $data;
    }

    public function parseSeries(Parser $parser, $id)
    {
        $series = KinoxSeries::firstOrNew(['id' => $id]);

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
                $episode = KinoxEpisode::firstOrNew(['series_id' => $id, 'season' => $season, 'episode' => $episode]);
                if ($episode->isDirty())
                {
                    $episode->save();
                    if ($series->created_at != $series->updated_at) {
                        Event::fire(NewEpisodeEvent::Kinox($series, $episode));
                    }
                }
            }
        }
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