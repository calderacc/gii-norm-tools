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

    public function setText(string $text): Absatz
    {
        $this->contentList[] = new AbsatzText($text);

        return $this;
    }

    public function getText(): ?string
    {
        /** @var AbsatzText $text */
        $text = array_pop($this->contentList);

        return $text->getText();
    }
}