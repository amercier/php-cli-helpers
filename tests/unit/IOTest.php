<?php

use Cli\Helpers\IO;

class IOUnitTestCase extends PHPUnit_Framework_TestCase
{
    protected $name = 'an apple';
    protected $values = array(
        'Braeburn',
        'Fuji',
        'Golden Delicious',
        'Granny Smith',
        'Jazz',
        'Jonagold',
        'Jonathan',
        'Pink Lady',
        'Red Delicious',
        'Royal Gala',
        'Sundowner',
    );

    protected function createInputStream($lines)
    {
        $inputStream = fopen('php://memory', 'wr');
        fwrite($inputStream, implode("\n", $lines) . "\n");
        fseek($inputStream, 0);
        return $inputStream;
    }

    protected function getValuesOutput()
    {
        $output = array();
        foreach ($this->values as $i => $value) {
            array_push($output, ($i + 1 < 10 ? ' ' : '') . ($i + 1) . '. ' . $value);
        }
        return $output;
    }

    public function testFormIsDisplayedCorrectly()
    {

        IO::form($this->name, $this->values, $this->createInputStream(array('6')));

        $this->expectOutputString(implode("\n", array_merge(
            $this->getValuesOutput(),
            array(
                '',
                'Choose ' . $this->name . ': ',
            )
        )));
    }

    public function testFormReturnedValueIsCorrect()
    {
        ob_start(); // as we do not capture output using expectOutputString
        $result = IO::form($this->name, $this->values, $this->createInputStream(array('6')));
        ob_end_clean();

        $this->assertEquals('integer', gettype($result));
        $this->assertEquals(5, $result);
    }

    public function testFormDisplaysErrorOnValueTooHigh()
    {
        IO::form($this->name, $this->values, $this->createInputStream(array('12','1')));

        $this->expectOutputString(implode("\n", array_merge(
            $this->getValuesOutput(),
            array(
                '',
                'Choose ' . $this->name . ': ',
                'Please enter a number between 1 and ' . count($this->values) . ': ',
            )
        )));
    }

    public function testFormDisplaysErrorOnValueTooLow()
    {
        IO::form($this->name, $this->values, $this->createInputStream(array('0','1')));

        $this->expectOutputString(implode("\n", array_merge(
            $this->getValuesOutput(),
            array(
                '',
                'Choose ' . $this->name . ': ',
                'Please enter a number between 1 and ' . count($this->values) . ': ',
            )
        )));
    }

    public function testStrPadAll()
    {
        $this->assertEquals(
              "#   EN          FR   ES\n"
            . "\n"
            . "1   One         Un   Uno\n"
            . "2   Two       Deux   Dos\n"
            . "3   Three    Trois   Tres\n"
            . "4   Four    Quatre   Cuatro\n"
            . "5   Five      Cinq   Cinco",
            IO::strPadAll(
                array(
                    array('#', 'EN', 'FR', 'ES'),
                    '',
                    array('1', 'One', 'Un', 'Uno'),
                    array('2', 'Two', 'Deux', 'Dos'),
                    array('3', 'Three', 'Trois', 'Tres'),
                    array('4', 'Four', 'Quatre', 'Cuatro'),
                    array('5', 'Five', 'Cinq', 'Cinco'),
                ),
                array(
                    2 => STR_PAD_LEFT,
                    3 => STR_PAD_RIGHT,
                ),
                "\n",
                '   '
            )
        );
    }
}
