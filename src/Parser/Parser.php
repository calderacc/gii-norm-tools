<?php

namespace Caldera\GiiNormTools\Parser;

use Caldera\GiiNormTools\GesetzTree\Absatz;
use Caldera\GiiNormTools\GesetzTree\Gesetz;
use Caldera\GiiNormTools\GesetzTree\ItemList;
use Caldera\GiiNormTools\GesetzTree\ListItem;
use Caldera\GiiNormTools\GesetzTree\Paragraph;
use Caldera\GiiNormTools\GesetzTree\Text;

class Parser implements ParserInterface
{
    /** @var \DOMDocument $xml */
    protected $xml;

    protected $gesetz;

    public function __construct()
    {
        $this->gesetz = new Gesetz();
    }

    public function loadXmlFile(string $filename): ParserInterface
    {
        $this->xml = new \DOMDocument();
        $this->xml->load($filename);

        return $this;
    }

    public function parse(): ParserInterface
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

        $paragraph
            ->setNummer($this->getParagraphNummer($norm))
            ->setTitel($this->getParagraphTitel($norm))
        ;

        if ($paragraph->getNummer() != '3') {
            return $paragraph;
        }

        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        /** @var \DOMNode $node */
        foreach ($nodeList as $node) {
            $absatz = $this->parseAbsatz($node);

            $paragraph->addAbsatz($absatz);
        }

        return $paragraph;
    }

    protected function parseList(\DOMElement $node): ItemList
    {
        $absatzList = new ItemList();
        $listItemNummer = null;

        for ($i = 0; $i < $node->childNodes->length; ++$i) {
            $childNode = $node->childNodes->item($i);

            if ($childNode->nodeName === 'DT') {
                $listItemNummer = $childNode->nodeValue;
            }

            if ($childNode->nodeName === 'DD') {
                $absatzListItem = $this->parseListItem($listItemNummer, $childNode);

                $absatzList->addListItem($absatzListItem);
            }
        }

        return $absatzList;
    }

    protected function parseListItem(string $listItemNummer, \DOMElement $node)
    {
        $absatzListItem = new ListItem();


        for ($i = 0; $i < $node->childNodes->length; ++$i) {
            $foo = $node->childNodes->item($i);

            var_dump($foo->childNodes->item(0));
        }


        /*
        if ($node->childNodes->length === 1) {
            $absatzListItem
                ->setNummer($listItemNummer)
                ->setText($node->nodeValue)
            ;
        } else {
            $list = new ItemList();



        }*/

        return $absatzListItem;
    }

    protected function parseAbsatz(\DOMNode $node): Absatz
    {
        $absatz = new Absatz();

        preg_match('/\(([0-9a-zA-Z]*)\)\ (.*)/', $node->nodeValue, $matches);

        if (count($matches) === 3) {
            $absatz->setNummer($matches[1]);

            for ($i = 0; $i < $node->childNodes->length; ++$i) {
                $subItem = $node->childNodes->item($i);

                if ($subItem->nodeName === '#text') {
                    $absatzText = new Text();

                    $string = $subItem->nodeValue;
                    $string = str_replace('('.$matches[1].') ', '', $string);
                    $absatzText->setText($string);

                    $absatz->addText($absatzText);
                }

                if ($subItem->nodeName === 'DL') {
                    $absatzList = $this->parseList($subItem);

                    $absatz->addList($absatzList);
                }
            }
        } else {
            $absatz->setTextString($node->nodeValue);
        }

        return $absatz;
    }

    protected function isNormParagraph(\DOMElement $norm): bool
    {
        $path = $norm->getNodePath().'/metadaten/enbez';

        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        if ($nodeList->length !== 0 && strpos($nodeList->item(0)->nodeValue, '§') === 0) {
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

    protected function getParagraphTitel(\DOMElement $norm): string
    {
        $path = $norm->getNodePath().'/metadaten/titel';

        $xpath = new \DOMXPath($this->xml);

        /** @var \DOMNodeList $nodeList */
        $nodeList = $xpath->query($path);

        $titel = $nodeList->item(0)->nodeValue;

        return $titel;
    }
}