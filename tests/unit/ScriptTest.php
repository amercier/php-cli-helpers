<?php

use Cli\Helpers\Script;
use Cli\Helpers\Parameter;

class ScriptUnitTestCase extends PHPUnit_Framework_TestCase
{
    protected $helloWorld;

    public function setUp()
    {
        $this->helloWorld = Script::create()
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
            ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
            ->setProgram(function($options) {
                if ($options['name'] === 'Adolf') {
                    throw new \Exception('That name is prohibited');
                }
                echo 'Hello, ' . $options['name'] . '!';
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
        Script::create()
            ->setExceptionCatchingEnabled(false)
            // ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->setProgram(function($options) {})
            ->start(array());
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testVersionRequired()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            // ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->setProgram(function($options) {})
            ->start(array());
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testDescriptionRequired()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            ->setVersion('1.0')
            // ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->setProgram(function($options) {})
            ->start(array());
    }

    public function testCopyrightNotRequired()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            // ->setCopyright('Copyright (c) Orange ECV 2013')
            ->setProgram(function($options) {
                return true;
            })
            ->start(array());
        $this->assertEquals(true, true);
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingScriptParameter
     */
    public function testProgramRequired()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            // ->setProgram(function($options) {
            //     return true;
            // })
            ->start(array());
        $this->assertEquals(true, true);
    }

    /**
     * @expectedException Cli\Helpers\Exception\DuplicateScriptParameter
     */
    public function testDuplicateShortSwitch()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            // ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name'    , 'World'), 'Set the name of the person to greet')
            ->addParameter(new Parameter('n', 'nickname', 'World'), 'Set the nickname of the person to greet')
            ->setProgram(function($options) {})
            ->start(array());
    }

    /**
     * @expectedException Cli\Helpers\Exception\DuplicateScriptParameter
     */
    public function testDuplicateLongSwitch()
    {
        Script::create()
            ->setExceptionCatchingEnabled(false)
            // ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Orange ECV 2013')
            ->addParameter(new Parameter('n', 'name', 'World'), 'Set the name of the person to greet')
            ->addParameter(new Parameter('M', 'name', 'World'), 'Set the nickname of the person to greet')
            ->setProgram(function($options) {})
            ->start(array());
    }

    public function testStartIsReturningValue()
    {
        $result = $this->helloWorld->start(array());
        $this->assertEquals('OK', $result);
        $this->expectOutputRegex('/.*/'); // triggers output buffering to prevent pollution
    }

    public function testStartIsDisplayingHelloWorld()
    {
        $result = $this->helloWorld->start(array());
        $this->expectOutputString("Hello, World!\n");
    }

    public function testStartIsDisplayingGivenNameWithShortSwitch()
    {
        $result = $this->helloWorld->start(array('script.php', '-n', 'Franky'));
        $this->expectOutputString("Hello, Franky!\n");
    }

    public function testStartIsDisplayingGivenNameWithLongSwitch()
    {
        $result = $this->helloWorld->start(array('script.php', '--name', 'Franky'));
        $this->expectOutputString("Hello, Franky!\n");
    }

    public function testStartIsDisplayingGivenVerbosityWithShortSwitch()
    {
        $result = $this->helloWorld->start(array('script.php', '--name', 'Franky', '-V'));
        $this->expectOutputString("Hello, Franky! Nice to see you again :)\n");
    }

    public function testStartIsDisplayingGivenVerbosityWithLongSwitch()
    {
        $result = $this->helloWorld->start(array('script.php', '--name', 'Franky', '--verbose'));
        $this->expectOutputString("Hello, Franky! Nice to see you again :)\n");
    }
}
