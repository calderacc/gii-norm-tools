<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Gesetz
{
    protected $paragraphList = [];

    public function __construct()
    {

    }

    public function addParagraph(Paragraph $paragraph): Gesetz
    {
        $this->paragraphList[$paragraph->getNummer()] = $paragraph;

        return $this;
    }

    public function getParagraphList(): array
    {
        return $this->paragraphList;
    }

    public function getParagraph(string $paragraphNummer): Paragraph
    {
        return $this->paragraphList[$paragraphNummer];
    }
}