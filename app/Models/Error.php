<?php namespace App\Models;

class Error
{
    public $code;
    public $message;
    public $fields;

    function __construct($code, $message, $fields)
    {
        $this->code = $code;
        $this->message = $message;
        $this->fields = $fields;
    }
}