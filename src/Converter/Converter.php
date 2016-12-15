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

    public function convert()
    {
        foreach ($this->xml->norm as $norm) {
            if (strpos($norm->metadaten->enbez, '§') !== false) {
                $paragraph = new Paragraph();

                preg_match('/§ (.*)/', $norm->metadaten->enbez, $matches);

                $paragraph->setNummer($matches[1]);

                $texts = $norm->textdaten->text->Content->P;

                foreach ($texts as $text) {
                    $absatz = new Absatz();

                    preg_match('/\(([0-9a-zA-Z]*)\)\ (.*)/', $text, $matches);

                    if (!$matches) {
                        continue;
                    }

                    $absatz
                        ->setNummer($matches[1])
                        ->setText($matches[2])
                    ;


                    $paragraph->addAbsatz($absatz);
                }

                $this->gesetz->addParagraph($paragraph);
            }
        }
    }
}