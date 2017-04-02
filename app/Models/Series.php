<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Series implements JsonSerializable
{
    /**
     * @var string
     */
    public $provider;

    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var Season[]
     */
    public $seasons = [];

    /**
     * Series constructor.
     * @param string $provider
     * @param string $id
     */
    public function __construct($provider, $id)
    {
        $this->provider = $provider;
        $this->id = $id;
    }

    /**
     * @param int $number
     * @return bool
     */
    public function ContainsSeason($number)
    {
        return array_first($this->seasons, function($season) use ($number) {
            return ($season->number == $number);
        }) !== null;
    }

    /**
     * @param Season $season
     * @return void
     */
    public function AddSeason($season)
    {
        if (!$this->ContainsSeason($season->number)) {
            array_push($this->seasons, $season);
        }
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