<?php

namespace Caldera\GiiNormTools\GesetzTree;

class ListItem
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

    public function setNummer(string $nummer): ListItem
    {
        $this->nummer = $nummer;

        return $this;
    }

    public function getNummer(): ?string
    {
        return $this->nummer;
    }

    public function setTextString(string $text): ListItem
    {
        $this->contentList = [];

        $this->contentList[] = new Text($text);

        return $this;
    }

    public function getTextString(): ?string
    {
        /** @var Text $text */
        $text = array_pop($this->contentList);

        if ($text instanceof Text) {
            return $text->getText();
        }

        return 'foo';
    }

    public function getContentList(): array
    {
        return $this->contentList;
    }

    public function addText(Text $absatzText): ListItem
    {
        array_push($this->contentList, $absatzText);

        return $this;
    }

    public function addList(ItemList $absatzList): ListItem
    {
        array_push($this->contentList, $absatzList);

        return $this;
    }
}