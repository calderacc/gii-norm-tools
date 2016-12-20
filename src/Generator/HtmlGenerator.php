<?php

namespace Caldera\GiiNormTools\Generator;

use Caldera\GiiNormTools\GesetzTree\Gesetz;
use Caldera\GiiNormTools\GesetzTree\Paragraph;

class HtmlGenerator
{
    /** @var Gesetz $gesetz */
    protected $gesetz;

    protected $html = '';

    public function __construct(Gesetz $gesetz)
    {
        $this->gesetz = $gesetz;
    }

    public function generate()
    {
        $this->html .= '<ul>';

        foreach ($this->gesetz->getParagraphList() as $paragraph) {
            $this->html .= $this->generateParagraph($paragraph);
        }

        $this->html .= '</ul>';
    }

    public function getContent(): string
    {
        return $this->html;
    }

    protected function generateParagraph(Paragraph $paragraph): string
    {
        return '<li>§ '.$paragraph->getNummer().'</li>';
    }
}