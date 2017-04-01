<?php

namespace App\Services;

class ParserService
{
    /**
     * @param string $page
     * @return Parser
     */
    public function from($page)
    {
        $parser = new Parser();
        $parser->loadHTML($page);

        return $parser;
    }
}