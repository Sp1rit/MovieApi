<?php

namespace App\Services;


use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;

class Parser
{
    private $doc;
    private $xpath;

    function __construct()
    {
        $this->doc = new DOMDocument('1.0', 'UTF-8');
    }

    public function loadHTML($page)
    {
        @$this->doc->loadHTML($page);
        $this->xpath = new DOMXPath($this->doc);
    }

    /**
     * @param string $xpath
     * @param DOMNode $context
     * @return DOMElement
     */
    public function getItem($xpath, $context = null)
    {
        if ($context == null)
            return $this->xpath->query($xpath)->item(0);
        else
            return $this->xpath->query($xpath, $context)->item(0);
    }

    /**
     * @param string $xpath
     * @param DOMNode $context
     * @return DOMNodeList
     */
    public function getItems($xpath, $context = null)
    {
        if ($context == null)
            return $this->xpath->query($xpath);
        else
            return $this->xpath->query($xpath, $context);
    }

    /**
     * @param string $xpath
     * @param DOMNode $context
     * @return string
     */
    public function getText($xpath, $context = null)
    {
        return $this->getItem($xpath, $context)->textContent;
    }

    /**
     * @param string $xpath
     * @param string $attribute
     * @param DOMNode $context
     * @return string
     */
    public function getAttribute($xpath, $attribute, $context = null)
    {
        return $this->getItem($xpath, $context)->getAttribute($attribute);
    }
}