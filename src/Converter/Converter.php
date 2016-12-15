<?php

namespace Caldera\GiiNormTools\Converter;

use Caldera\GiiNormTools\GesetzTree\Absatz;
use Caldera\GiiNormTools\GesetzTree\Gesetz;
use Caldera\GiiNormTools\GesetzTree\Paragraph;

class Converter
{
    protected $xml;

    protected $gesetz;

    public function __construct()
    {
        $this->gesetz = new Gesetz();
    }

    public function loadXmlFile(string $filename): Converter
    {
        $xmlFileContent = file_get_contents($filename);

        $this->xml = new \SimpleXMLElement($xmlFileContent);

        return $this;
    }

    public function convert(): Converter
    {
        foreach ($this->xml->norm as $norm) {
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

    protected function parseParagraph(\SimpleXMLElement $norm): Paragraph
    {
        $paragraph = new Paragraph();

        $paragraph->setNummer($this->getParagraphNummer($norm));

        $texts = $norm->textdaten->text->Content->P;

        foreach ($texts as $text) {
            $absatz = new Absatz();

            preg_match('/\(([0-9a-zA-Z]*)\)\ (.*)/', $text, $matches);

            if (!$matches) {
                continue;
            }

            $absatz
                ->setNummer($matches[1])
                ->setTextString($matches[2])
            ;


            $paragraph->addAbsatz($absatz);
        }

        return $paragraph;
    }

    protected function isNormParagraph(\SimpleXMLElement $norm): bool
    {
        return strpos($norm->metadaten->enbez, '§') !== false;
    }

    protected function getParagraphNummer(\SimpleXMLElement $norm): string
    {
        preg_match('/§ (.*)/', $norm->metadaten->enbez, $matches);

        return $matches[1];
    }
}