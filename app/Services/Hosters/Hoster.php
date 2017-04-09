<?php namespace App\Services\Hosters;

interface Hoster
{
    public function getMediaUrl($host, $media_id, &$url);

    public function getUrl($host, $media_id);

    public function getHostAndId($url);

    public function validUrl($url);

}
