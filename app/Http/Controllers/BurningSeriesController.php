<?php namespace App\Http\Controllers;

use App\Events\NewEpisodeEvent;
use App\Models\BurningSeries\Mirror;
use App\Models\BurningSeries\Series;
use App\Models\BurningSeries\Episode;
use App\Models\MediaResult;
use App\Services\ParserService as Parser;
use Curl;
use Event;

class BurningSeriesController extends Controller
{
    const PROVIDER = "bs";
    const URL_BASE = "https://bs.to/api";
    const URL_SERIES_LIST = self::URL_BASE . "/series";
    const URL_SERIES = self::URL_BASE . "/series/%s/%s";
    const URL_MIRRORS = self::URL_BASE . "/series/%s/%s/%s";
    const HOSTERS = [
/*        'Streamcloud' => [
            'class' => 'App\Services\Hosters\Streamcloud',
            'mp4' => true,
            'proxy' => true,
            'premium' => false,
            'wait' => 0
        ],*/
        'Vivo' => [
            'class' => 'App\Services\Hosters\Vivo',
            'mp4' => true,
            'proxy' => true,
            'premium' => false,
            'wait' => 0
        ],
        'Shared' => [
            'class' => 'App\Services\Hosters\Shared',
            'mp4' => true,
            'proxy' => true,
            'premium' => false,
            'wait' => 0
        ],
        'PowerWatch' => [
            'class' => 'App\Services\Hosters\PowerWatch',
            'mp4' => true,
            'proxy' => true,
            'premium' => false,
            'wait' => 5
        ],
        'Novamov' => [
            'class' => '',
            'mp4' => false,
            'proxy' => false,
            'premium' => false,
            'wait' => 0
        ],
        'MovShare' => [
            'class' => '',
            'mp4' => false,
            'proxy' => false,
            'premium' => false,
            'wait' => 0
        ],
        'NowVideo' => [
            'class' => '',
            'mp4' => false,
            'proxy' => false,
            'premium' => false,
            'wait' => 0
        ],
        'VideoWeed' => [
            'class' => '',
            'mp4' => false,
            'proxy' => false,
            'premium' => false,
            'wait' => 0
        ]
    ];

    const PUBLIC_KEY = "PgfLa3cGNY5nDN3isibzuGsomSWspjAs";
    const HMAC_KEY = "FSOGiKFVdaJmJH1axROzqcS8s8jhV3UT";

    public function LoadSeriesList()
    {
        $response = Curl::to(self::URL_SERIES_LIST)
            ->withHeader('BS-Token: ' . $this->GenerateToken(self::URL_SERIES_LIST))
            ->get();

        if (!empty($response)) {
            $object = json_decode($response);
            if ($object != null && is_array($object)) {
                foreach ($object as $series) {
                    Series::updateOrCreate(['id' => $series->id], ['name' => $series->series]);
                }
            }
        }
    }

    public function Search($search)
    {
        $searchResults = Series::search($search);

        $results = [];
        foreach($searchResults as $searchResult) {
            $result = new MediaResult(self::PROVIDER, 'series');
            $result->id = $searchResult->id;
            $result->name = $searchResult->name;
            $result->language = null;

            array_push($results, $result);
        }

        return response()->json($results);
    }

    public function Series($id)
    {
        $series = Series::find($id);

        if ($series->updateDue())
            $this->parseSeries($series->id);

        $data = new MediaResult(self::PROVIDER, 'series');
        $data->id = $series->id;
        $data->name = $series->name;
        $data->seasons = [];
        foreach($series->episodes as $episode) {
            if (!array_key_exists($episode->season, $data->seasons)) {
                $data->seasons[$episode->season] = new \stdClass();
                $data->seasons[$episode->season]->language = $series->episodes()->whereRaw("season = ? AND german = ''", [$episode->season])->count() > 0 ? $series->episodes()->whereRaw("season = ? AND german != ''", [$episode->season])->count() > 0 ? 'de/en' : 'en' : 'de';
                $data->seasons[$episode->season]->episodes = [];
            }
            $data->seasons[$episode->season]->episodes[$episode->episode] = new \stdClass();
            $data->seasons[$episode->season]->episodes[$episode->episode]->name = $episode->name();
            $data->seasons[$episode->season]->episodes[$episode->episode]->language = $episode->language();
        }

        return response()->json($data);
    }

    public function Season($id, $season)
    {
        $series = Series::find($id);

        if ($series->updateDue())
            $this->parseSeries($series->id);

        $data = new MediaResult(self::PROVIDER, 'series');
        $data->id = $series->id;
        $data->name = $series->name;
        $data->season = $season;
        $data->episodes = array();
        foreach ($series->episodes()->where('season', $season)->get() as $episode) {
            $data->episodes[$episode->episode] = new \stdClass();
            $data->episodes[$episode->episode]->episode = $episode->episode;
            $data->episodes[$episode->episode]->name = $episode->name();
            $data->episodes[$episode->episode]->language = $episode->language();
        }
        $data->episodes = array_values($data->episodes);

        return response()->json($data);
    }

    public function Episode($id, $season, $episode)
    {
        $series = Series::find($id);

        if ($series->updateDue())
            $this->parseSeries($series->id);

        $this->parseMirrors($id, $season, $episode);

        $data = new MediaResult(self::PROVIDER, 'series');
        $data->id = $series->id;
        $data->name = $series->name;
        $data->season = $season;
        $data->episode = $episode;
        $data->mirrors = [];
        foreach(Mirror::getBy($id, $season, $episode) as $mirror)
        {
            $data->mirrors[] = new \stdClass();
            $dataMirror = &$data->mirrors[count($data->mirrors) - 1];
            $dataMirror->name = $mirror->hoster;
            $dataMirror->id = $mirror->hoster;
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

    private function parseSeries($id)
    {
        $series = Series::find($id);

        // TODO: Check for END
        $s = $series->created_at == $series->updated_at ? 1 : \DB::select("SELECT MIN(season) As season FROM
(
    SELECT MAX(season) AS season FROM video_bs_episodes WHERE series_id = ?
    UNION
    SELECT MIN(season) AS season FROM video_bs_episodes where series_id = ? AND german = ''
) AS seasons", [$series->id, $series->id])[0]->season;
        $maxSeasons = $s;

        for (; $s <= $maxSeasons; $s++) {
            $url = sprintf(self::URL_SERIES, $series->id, $s);
            $json = Curl::to($url)
                ->withHeader('BS-Token: ' . $this->GenerateToken($url))
                ->get();
            $object = json_decode($json);
            $maxSeasons = $object->series->seasons;
            foreach ($object->epi as $epi) {
                $episode = Episode::firstOrNew(['series_id' => $series->id, 'season' => $object->season, 'episode' => $epi->epi]);
                if ($episode->isDirty()) {
                    if ($series->created_at != $series->updated_at) {
                        // Episode was added or updated
                        Event::fire(NewEpisodeEvent::Bs($series, $episode));
                    }
                }
                else if ($episode->german != $epi->german) {
                    // German episode was released
                    Event::fire(NewEpisodeEvent::Bs($series, $episode));
                }
                $episode->fill(['german' => $epi->german, 'english' => $epi->english]);
                $episode->save();
            }
            Series::updateOrCreate(['id' => $series->id], ['end' => $object->series->end]);
            sleep(1);
        }
        $series->touch();
    }

    private function parseMirrors($id, $season, $episode)
    {
        $url = sprintf(self::URL_MIRRORS, $id, $season, $episode);
        $json = Curl::to($url)
            ->withHeader('BS-Token: ' . $this->GenerateToken($url))
            ->get();
        $object = json_decode($json);

        foreach ($object->links as $mirror) {
            Mirror::updateOrCreate(['media_id' => $id, 'season' => $season, 'episode' => $episode, 'hoster' => $mirror->hoster],
                ['mirror_id' => $mirror->id]);
        }
    }

    /**
     * @param $url
     * @return string
     */
    private function GenerateToken($url)
    {
        $timestamp = time();
        $hmac = hash_hmac('sha256', $timestamp . str_replace(self::URL_BASE, '', $url), self::HMAC_KEY);
        $string = '{"timestamp":'.$timestamp.',"hmac":"'.$hmac.'","public_key":"'.self::PUBLIC_KEY.'"}';
        $base64Key = base64_encode($string);

        return $base64Key;
    }
}