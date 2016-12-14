<?php

namespace Caldera\GiiNormTools\Converter;

use Caldera\GiiNormTools\GesetzTree\Gesetz;

class Converter
{
    protected $xml;

    protected $gesetz;

    public function __construct()
    {
        $this->gesetz = new Gesetz();
    }

    public function loadXmlFile(string $filename): Converter
    {
        $xmlFileContent = file_get_contents($filename);

        $this->xml = new \SimpleXMLElement($xmlFileContent);

        return $this;
    }
}