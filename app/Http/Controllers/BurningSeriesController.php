<?php namespace App\Http\Controllers;

use App\Models\BurningSeries\Series;
use App\Models\MediaResult;
use App\Services\ParserService as Parser;
use Curl;

class BurningSeriesController extends Controller
{
    const PROVIDER = "bs";
    const URL_BASE = "https://bs.to/api";
    const URL_SERIES_LIST = self::URL_BASE . "/series";

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

        return $results;
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