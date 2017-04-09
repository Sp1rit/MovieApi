<?php namespace App\Services\Hosters;

use Curl;

class Vivo implements Hoster
{
    public function getMediaUrl($host, $media_id, &$url)
    {
        $web_url = $this->getUrl($host, $media_id);
        $html = Curl::to($web_url)->get();
		
		if (preg_match('/Core\.InitializeStream \(\'(.+?)\'\);/', $html, $matches)) {
			$url = json_decode(base64_decode($matches[1]))[1];
			return true;
		}
		
        //if (!preg_match('/class="stream-content" data-url/', $html))
        //    throw new \Exception("File not found or removed");

        $form_values = [];
        preg_match_all("/<input.*?name=\"(.*?)\".*?value=\"(.*?)\".*?>/", $html, $matches, PREG_SET_ORDER);
        foreach($matches as $match)
            $form_values[$match[1]] = str_replace('download1', 'download2', $match[2]);

        $html = Curl::to($web_url)->withData($form_values)->post();
        if (preg_match('/data-url="?(.+?)"/', $html, $matches)) {
            $url = $matches[1];
            return true;
        } else {
            return false;
        }
    }

    public function getUrl($host, $media_id)
    {
        return sprintf('https://vivo.sx/%s', $media_id);
    }

    public function getHostAndId($url)
    {
        if (preg_match('/http:\/\/(?:www.)?(.+?)\/([0-9A-Za-z]+)/', $url, $matches))
            return array_slice($matches, 1);
        else
            return false;
    }

    public function validUrl($url)
    {
        return preg_match('/https:\/\/(www.)?vivo.sx\/[0-9A-Za-z]+/', $url);
    }
}