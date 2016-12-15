<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Absatz
{
    protected $nummer;

    protected $text;

    public function __construct(string $nummer = null, string $text = null)
    {
        $this->nummer = $nummer;

        $this->text = $text;
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