<?php namespace App\Models;

class MediaResult
{
    public $provider;
    public $type;

    private $properties = [];

    function __construct($provider, $type)
    {
        $this->provider = $provider;
        $this->type = $type;
    }

    function &__get($name)
    {
        if (isset($this->$name))
            return $this->$name;

        return null;
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }

}