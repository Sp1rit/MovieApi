<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Season implements JsonSerializable
{
    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $language;

    /**
     * @var Episode[]
     */
    public $episodes = [];

    /**
     * Season constructor.
     * @param int $number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * @param Episode $episode
     */
    public function AddEpisode($episode)
    {
        array_push($this->episodes, $episode);
    }

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