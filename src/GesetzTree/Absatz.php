<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Absatz
{
    protected $nummer;

    protected $text;

    public function __construct()
    {

    }

    public function setNummer(string $nummer): Absatz
    {
        $this->nummer = $nummer;

        return $this;
    }

    public function getNummer(): string
    {
        return $this->nummer;
    }

    public function setText(string $text): Absatz
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }
}