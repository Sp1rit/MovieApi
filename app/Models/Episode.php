<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Episode implements JsonSerializable
{
    /**
     * @var int
     */
    public $number;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $language;

    /**
     * @var Mirror[]
     */
    public $mirrors = [];

    /**
     * Episode constructor.
     * @param int $number
     */
    public function __construct($number)
    {
        $this->number = $number;
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