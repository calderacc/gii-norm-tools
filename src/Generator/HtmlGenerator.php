<?php

namespace Caldera\GiiNormTools\Generator;

use Caldera\GiiNormTools\GesetzTree\Gesetz;

class HtmlGenerator
{
    protected $gesetz;

    public function __construct(Gesetz $gesetz)
    {
        $this->gesetz = $gesetz;
    }
}