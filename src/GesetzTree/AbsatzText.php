<?php

namespace Caldera\GiiNormTools\GesetzTree;

class AbsatzText
{
    protected $text;

    public function __construct(string $text = null)
    {
        $this->text = $text;
    }

    public function setText(string $text): AbsatzText
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }
}