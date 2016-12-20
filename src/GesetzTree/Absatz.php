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
            $this->contentList[] = new Text($text);
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

        $this->contentList[] = new Text($text);

        return $this;
    }

    public function getTextString(): ?string
    {
        /** @var Text $text */
        $text = array_pop($this->contentList);

        return $text->getText();
    }

    public function getContentList(): array
    {
        return $this->contentList;
    }

    public function addText(Text $absatzText): Absatz
    {
        array_push($this->contentList, $absatzText);

        return $this;
    }

    public function addList(ItemList $absatzList): Absatz
    {
        array_push($this->contentList, $absatzList);

        return $this;
    }
}