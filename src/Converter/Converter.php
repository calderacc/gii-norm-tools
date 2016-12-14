<?php

namespace Caldera\GiiNormTools\Converter;

use Caldera\GiiNormTools\GesetzTree\Gesetz;

class Converter
{
    protected $gesetz;

    public function __construct()
    {
        $this->gesetz = new Gesetz();
    }

}