<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Paragraph
{
    protected $nummer;

    protected $absatzList = [];

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

    public function addAbsatz(Absatz $absatz): Paragraph
    {
        $this->absatzList[$absatz->getNummer()] = $absatz;

        return $this;
    }

    public function getAbsatzList(): array
    {
        return $this->absatzList;
    }

    public function getAbsatz(string $nummer): Absatz
    {
        return $this->absatzList[$nummer];
    }
}