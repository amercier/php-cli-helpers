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
        return array_map(
            function($i) {
                return ($i + 1 < 10 ? ' ' : '') . ($i + 1) . '. ' . $this->values[$i];
            },
            array_keys($this->values)
        );
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
}
