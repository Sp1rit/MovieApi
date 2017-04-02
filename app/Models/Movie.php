<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Movie implements JsonSerializable
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
     * @var Mirror[]
     */
    public $mirrors = [];

    /**
     * Movie constructor.
     * @param string $provider
     * @param string $id
     */
    public function __construct($provider, $id)
    {
        $this->provider = $provider;
        $this->id = $id;
    }

    /**
     * @param Mirror $mirror
     */
    public function AddMirror($mirror)
    {
        array_push($this->mirrors, $mirror);
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