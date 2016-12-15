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

    public function testConverter2()
    {
        $filename =  __DIR__ . '/../files/stvo.xml';

        $converter = new Converter();

        $actualAbsatz = $converter
            ->loadXmlFile($filename)
            ->convert()
            ->getGesetz()
            ->getParagraph('2')
            ->getAbsatz('4')
        ;

        $expectedAbsatz = new Absatz(
            '4',
            'Mit Fahrrädern muss einzeln hintereinander gefahren werden; nebeneinander darf nur gefahren werden, wenn dadurch der Verkehr nicht behindert wird. Eine Pflicht, Radwege in der jeweiligen Fahrtrichtung zu benutzen, besteht nur, wenn dies durch Zeichen 237, 240 oder 241 angeordnet ist. Rechte Radwege ohne die Zeichen 237, 240 oder 241 dürfen benutzt werden. Linke Radwege ohne die Zeichen 237, 240 oder 241 dürfen nur benutzt werden, wenn dies durch das allein stehende Zusatzzeichen „Radverkehr frei“ angezeigt ist. Wer mit dem Rad fährt, darf ferner rechte Seitenstreifen benutzen, wenn keine Radwege vorhanden sind und zu Fuß Gehende nicht behindert werden. Außerhalb geschlossener Ortschaften darf man mit Mofas Radwege benutzen.'
        );

        $this->assertEquals($expectedAbsatz, $actualAbsatz);
    }
}