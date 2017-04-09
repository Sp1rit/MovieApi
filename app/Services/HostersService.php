<?php namespace App\Services;

use App\Http\Controllers\BurningSeriesController;
use App\Http\Controllers\KinoxController;
use App\Http\Controllers\Movie4kController;

class HostersService
{
    private $hosters;

    function __construct()
    {
        $this->hosters = array_merge(BurningSeriesController::HOSTERS, KinoxController::HOSTERS, Movie4kController::HOSTERS);
    }

    /**
     * Resolves the video url from the hoster
     * @param string $hoster
     * @param string $url
     * @return mixed[]
     */
    public function resolve($hoster, $url)
    {
        $isProxy = false;
        $exists = false;
        $proxyUrl = null;

        if (array_key_exists($hoster, $this->hosters)) {
            $hoster = $this->hosters[$hoster];

            if ($hoster['proxy']) {
                $isProxy = true;
                $class = $hoster['class'];
                $instance = new $class();
                list($host, $id) = $instance->getHostAndId($url);
                if ($instance->getMediaUrl($host, $id, $url)) {
                    $proxyUrl = url('Proxy', ['url' => urlencode(base64_encode($url))]);
                    $exists = true;
                }
                return [$proxyUrl, $isProxy, $exists];
            }
        }

        return [$proxyUrl, $isProxy, $exists];
    }

    /**
     * Resolve the video url from the hoster
     * @param string $url
     * @return boolean[]
     */
    public function resolveByUrl(&$url)
    {
        $isProxy = false;
        $exists = false;

        foreach($this->hosters as $hoster)
        {
            if ($hoster['proxy']) {
                $isProxy = true;

                $class = $hoster['class'];
                $instance = new $class();

                if ($instance->validUrl($url)) {
                    list($host, $id) = $instance->getHostAndId($url);
                    if ($instance->getMediaUrl($host, $id, $url)) {
                        $url = url('Proxy', ['url' => urlencode(base64_encode($url))]);
                        $exists = true;
                    }
                    return [$isProxy, $exists];
                }
            }
        }

        return [$isProxy, $exists];
    }
}
