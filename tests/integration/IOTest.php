<?php

require_once dirname(__FILE__) . '/AbstractScriptTestCase.php';

class IOIntegrationTestCase extends AbstractCliScriptTestCase
{
    const SCRIPT = 'php data/test-io.php';

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

    protected function getValuesOutput()
    {
        return array_map(
            function($i) {
                return ($i + 1 < 10 ? ' ' : '') . ($i + 1) . '. ' . $this->values[$i];
            },
            array_keys($this->values)
        );
    }

    public function testFormIsDisplayedCorrectlyAndReturnCorrectValue()
    {
        $this->assertScriptOutput(
            self::SCRIPT . ' "' . $this->name . '" "' . implode('" "', $this->values) . '"',
            implode("\n", array_merge(
                $this->getValuesOutput(),
                array(
                    '',
                    'Choose ' . $this->name . ': ',
                    'Response: int(0)',
                    '',
                )
            )),
            '',
            0,
            "1\n"
        );
    }
}
