<?php

namespace Caldera\GiiNormTools\Test\Converter;

use Caldera\GiiNormTools\Converter\Converter;
use Caldera\GiiNormTools\GesetzTree\Absatz;
use Caldera\GiiNormTools\GesetzTree\Paragraph;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConverter1()
    {
        $filename =  __DIR__ . '/../files/stvo.xml';

        $converter = new Converter();

        $actualParagraph = $converter
            ->loadXmlFile($filename)
            ->convert()
            ->getGesetz()
            ->getParagraph('1');

        $absatz1 = new Absatz('1', 'Die Teilnahme am Straßenverkehr erfordert ständige Vorsicht und gegenseitige Rücksicht.');
        $absatz2 = new Absatz('2', 'Wer am Verkehr teilnimmt hat sich so zu verhalten, dass kein Anderer geschädigt, gefährdet oder mehr, als nach den Umständen unvermeidbar, behindert oder belästigt wird.');

        $expectedParagraph = new Paragraph();
        $expectedParagraph
            ->setNummer('1')
            ->addAbsatz($absatz1)
            ->addAbsatz($absatz2)
        ;

        $this->assertEquals($expectedParagraph, $actualParagraph);
    }
}