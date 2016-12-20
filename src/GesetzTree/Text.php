<?php

namespace Caldera\GiiNormTools\GesetzTree;

class Text
{
    protected $text;

    public function __construct(string $text = null)
    {
        $this->text = $text;
    }

    public function setText(string $text): Text
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }
}