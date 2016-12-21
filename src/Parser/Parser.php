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

    protected function parseAbsatz(\DOMNode $node): Absatz
    {
        $absatz = new Absatz();

        preg_match('/\(([0-9a-zA-Z]*)\)\ (.*)/', $node->nodeValue, $matches);

        if (count($matches) === 3) {
            $absatz->setNummer($matches[1]);

            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeName === '#text') {
                    $absatzText = new Text();

                    $string = $childNode->nodeValue;
                    $string = str_replace('('.$matches[1].') ', '', $string);
                    $absatzText->setText($string);

                    $absatz->addText($absatzText);
                }

                if ($childNode->nodeName === 'DL') {
                    $absatzList = $this->parseList($childNode);

                    $absatz->addList($absatzList);
                }
            }
        } else {
            $absatz->setTextString($node->nodeValue);
        }

        return $absatz;
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

    protected function parseSublist(\DOMElement $node)
    {
        foreach ($node->childNodes as $childNode) {
            var_dump($childNode);
        }

    }

    protected function parseListItem(string $listItemNummer, \DOMElement $node)
    {
        $listItem = new ListItem();

        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeName === '#text') {
                $text = new Text();
                $text->setText($childNode->childNodes->item(0));

                $listItem->addText($text);
            }

            if ($childNode->nodeName === 'LA') {
                $this->parseSublist($childNode);
            }
        }
        /*
        for ($i = 0; $i < $node->childNodes->length; ++$i) {
            $itemNode = $node->childNodes->item($i);

            if ($itemNode->hasChildNodes()) {
                for ($i = 0; $i < $itemNode->childNodes->length; ++$i) {
                    $subItem = $itemNode->childNodes->item($i);

                    if ($subItem instanceof \DOMText) {
                        $text = new Text();
                        $text->setText($subItem->nodeValue);

                        $listItem->addText($text);
                    } else {
                        //$this->parseList($subItem);
                    }
                }
            } else {
                $text = new Text();
                $text->setText($itemNode->childNodes->item(0)->nodeValue);

                $listItem->addText($text);
            }
        }
*/

        /*
        if ($node->childNodes->length === 1) {
            $absatzListItem
                ->setNummer($listItemNummer)
                ->setText($node->nodeValue)
            ;
        } else {
            $list = new ItemList();



        }*/

        return $listItem;
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