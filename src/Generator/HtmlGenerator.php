<?php

namespace Caldera\GiiNormTools\Generator;

use Caldera\GiiNormTools\GesetzTree\Absatz;
use Caldera\GiiNormTools\GesetzTree\Gesetz;
use Caldera\GiiNormTools\GesetzTree\ItemList;
use Caldera\GiiNormTools\GesetzTree\ListItem;
use Caldera\GiiNormTools\GesetzTree\Paragraph;
use Caldera\GiiNormTools\GesetzTree\Text;

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
            $this->html .= "\n";
        }

        $this->html .= '</ul>';
    }

    public function getContent(): string
    {
        return $this->html;
    }

    protected function generateParagraph(Paragraph $paragraph): string
    {
        $html = '<li>';
        $html .= '<h3>§ '.$paragraph->getNummer().': '.$paragraph->getTitel().'</h3>';

        $absatzList = $paragraph->getAbsatzList();

        if (count($absatzList) === 1) {
            /** @var Absatz $absatz */
            $absatz = array_pop($absatzList);

            $html .= '<p>' . $absatz->getTextString() . '</p>';
        } else {
            $html .= '<ul>';

            foreach ($paragraph->getAbsatzList() as $absatz) {
                $html .= $this->generateAbsatz($absatz);
            }

            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }

    protected function generateAbsatz(Absatz $absatz): string
    {
        $html = '<p>(' . $absatz->getNummer() . ') ';

        foreach ($absatz->getContentList() as $absatzItem) {
            if ($absatzItem instanceof Text) {
                $html .= $absatzItem->getText();
            }

            if ($absatzItem instanceof ItemList) {
                $html .= $this->generateAbsatzList($absatzItem);
            }
        }

        $html .= '</p>';

        return $html;
    }

    protected function generateAbsatzList(ItemList $absatzList): string
    {
        $html = '<ul>';

        /** @var ListItem $absatzListItem */
        foreach ($absatzList->getItems() as $absatzListItem) {
            $html .= '<li>';
            $html .= $absatzListItem->getNummer() . ' ';
            $html .= $absatzListItem->getText();
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}