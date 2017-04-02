<?php namespace App\Models;

use JsonSerializable;
use ReflectionClass;

class Mirror implements JsonSerializable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $mp4 = false;

    /**
     * @var boolean
     */
    public $proxy = false;

    /**
     * @var int
     */
    public $wait;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
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