<?php

namespace Caldera\GiiNormTools\Converter;

use Caldera\GiiNormTools\GesetzTree\Absatz;
use Caldera\GiiNormTools\GesetzTree\AbsatzList;
use Caldera\GiiNormTools\GesetzTree\AbsatzListItem;
use Caldera\GiiNormTools\GesetzTree\AbsatzText;
use Caldera\GiiNormTools\GesetzTree\Gesetz;
use Caldera\GiiNormTools\GesetzTree\Paragraph;

class Converter
{
    /** @var \DOMDocument $xml */
    protected $xml;

    protected $gesetz;

    public function __construct()
    {
        $this->gesetz = new Gesetz();
    }

    public function loadXmlFile(string $filename): Converter
    {
        $this->xml = new \DOMDocument();
        $this->xml->load($filename);

        return $this;
    }

    public function convert(): Converter
    {
        $normList = $this->xml->getElementsByTagName('norm');

        foreach ($normList as $norm) {
            if ($this->isNormParagraph($norm)) {
                $paragraph = $this->parseParagraph($norm);

                $this->gesetz->addParagraph($paragraph);
            }
        }

        return $this;
    }

    public function getGesetz(): Gesetz
    {
        return $this->gesetz;
    }

    protected function parseParagraph(\DOMElement $norm): Paragraph
    {
        $path = $norm->getNodePath().'/textdaten/text/Content/P';

        $paragraph = new Paragraph();

        //$paragraph->setNummer($this->getParagraphNummer($norm));
        $paragraph->setNummer(8);
        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        if ($paragraph->getNummer() === '8') {
            /** @var \DOMNode $node */
            foreach ($nodeList as $node) {
                for ($i = 0; $i < $node->childNodes->length; ++$i) {
                    $subItem = $node->childNodes->item($i);

                    if ($subItem->nodeName === '#text') {
                        $absatzSubItem = new AbsatzText();

                        $absatzSubItem->setText($subItem->nodeValue);
                    }

                    if ($subItem->nodeName === 'DL') {
                        $this->parseList($subItem);
                    }
                }

                //$absatz = $this->parseAbsatz($p);

                //$paragraph->addAbsatz($absatz);
            }

        }

        return $paragraph;
    }

    protected function parseList(\DOMElement $node): AbsatzList
    {
        $absatzList = new AbsatzList();
        $listItemNummer = null;

        for ($i = 0; $i < $node->childNodes->length; ++$i) {
            $childNode = $node->childNodes->item($i);

            if ($childNode->nodeName === 'DT') {
                $listItemNummer = $childNode->nodeValue;
            }

            if ($childNode->nodeName === 'DD') {
                $absatzListItem = new AbsatzListItem();

                $absatzListItem
                    ->setNummer($listItemNummer)
                    ->setText($childNode->nodeValue)
                ;

                $absatzList->addListItem($absatzListItem);
            }
        }

        return $absatzList;
    }

    protected function parseAbsatz(\SimpleXMLElement $p): Absatz
    {
        $absatz = new Absatz();

        preg_match('/\(([0-9a-zA-Z]*)\)\ (.*)/', $p, $matches);

        $absatz
            ->setNummer($matches[1])
            ->setTextString($matches[2])
        ;

        return $absatz;
    }

    protected function isNormParagraph(\DOMElement $norm): bool
    {
        $path = $norm->getNodePath().'/metadaten/enbez';

        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        if ($nodeList->length !== 0 && $nodeList->item(0)->nodeValue[0] === '§') {
            return true;
        }

        return false;
    }

    protected function getParagraphNummer(\DOMElement $norm): string
    {
        $path = $norm->getNodePath().'/metadaten/enbez';

        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        $enbez = $nodeList->item(0)->nodeValue;

        preg_match('/§ (.*)/', $enbez, $matches);

        return $matches[1];
    }
}