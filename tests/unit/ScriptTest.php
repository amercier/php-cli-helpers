<?php

use Cli\Helpers\Script;
use Cli\Helpers\Parameter;

class ScriptUnitTestCase extends PHPUnit_Framework_TestCase
{
    protected $helloWorld;

    public function setUp()
    {
        $this->helloWorld = Cli\Helpers\Script::create()
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {
                print_r($options);
                echo 'Hello, ' . $options['name'];
                if ($options['verbose']) {
                    echo ' Nice to see you again :)';
                }
                echo "\n";
                return 'OK';
            });
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testNameRequired()
    {
        Cli\Helpers\Script::create()
            // ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {})
            ->start(array());
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testVersionRequired()
    {
        Cli\Helpers\Script::create()
            ->setName('Hello')
            // ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {})
            ->start(array());
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testDescriptionRequired()
    {
        Cli\Helpers\Script::create()
            ->setName('Hello')
            ->setVersion('1.0')
            // ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {})
            ->start(array());
    }

    public function testCopyrightNotRequired()
    {
        Cli\Helpers\Script::create()
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            // ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {
                return true;
            })
            ->start(array());
        $this->assertEquals(true, true);
    }

    public function testStartIsReturningValue()
    {
        $result = $this->helloWorld->start(array());
        $this->assertEquals('OK', $result);
    }

}
