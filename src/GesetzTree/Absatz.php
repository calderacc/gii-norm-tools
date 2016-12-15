<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Absatz
{
    protected $nummer;

    protected $contentList = [];

    public function __construct(string $nummer = null, string $text = null)
    {
        $this->nummer = $nummer;

        if ($text) {
            $this->contentList[] = new AbsatzText($text);
        }
    }

    public function setNummer(string $nummer): Absatz
    {
        $this->nummer = $nummer;

        return $this;
    }

    public function getNummer(): ?string
    {
        return $this->nummer;
    }

    public function setTextString(string $text): Absatz
    {
        $this->contentList = [];

        $this->contentList[] = new AbsatzText($text);

        return $this;
    }

    public function getTextString(): ?string
    {
        /** @var AbsatzText $text */
        $text = array_pop($this->contentList);

        return $text->getText();
    }
}