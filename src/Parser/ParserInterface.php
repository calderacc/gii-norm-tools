<?php

namespace Caldera\GiiNormTools\Parser;

use Caldera\GiiNormTools\GesetzTree\Gesetz;

interface ParserInterface
{
    public function loadXmlFile(string $filename): ParserInterface;
    public function parse(): ParserInterface;
    public function getGesetz(): Gesetz;
}