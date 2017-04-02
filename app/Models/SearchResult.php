<?php namespace App\Models;

class SearchResult
{
    /**
     * @var string
     */
    public $provider;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $language;

    public function __construct($provider, $type, $id, $name)
    {
        $this->provider = $provider;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
    }
}