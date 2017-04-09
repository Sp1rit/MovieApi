<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Stream implements JsonSerializable
{
    /**
     * @var string
     */
    public $hoster;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $proxyUrl;

    /**
     * @var int
     */
    public $nextSeason;

    /**
     * @var int
     */
    public $nextEpisode;

    /**
     * @var int
     */
    public $previousSeason;

    /**
     * @var int
     */
    public $previousEpisode;

    function jsonSerialize()
    {
        $reflection = new ReflectionClass(self::class);
        $properties = $reflection->getProperties();

        $retVal = [];
        foreach ($properties as $property)
        {
            $value = $property->getValue($this);
            if (is_array($value) && count($value) > 0 || !is_array($value) && $value !== null) {
                $retVal[$property->name] = $value;
            }
        };

        return $retVal;
    }
}