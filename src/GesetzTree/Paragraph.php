<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Paragraph
{
    protected $nummer;


    public function __construct()
    {

    }

    public function setNummer(string $nummer): Paragraph
    {
        $this->nummer = $nummer;

        return $this;
    }

    public function getNummer(): string
    {
        return $this->nummer;
    }
}