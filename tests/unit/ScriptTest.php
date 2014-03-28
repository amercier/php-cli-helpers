<?php

use Cli\Helpers\Script;
use Cli\Helpers\Parameter;

class ScriptUnitTestCase extends PHPUnit_Framework_TestCase
{
    protected $helloWorld;

    public function setUp()
    {
        $this->helloWorld = new Script();
        $this->helloWorld
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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
        $script = new Script();
        $script
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

    public function testNonBlockingParameterCallbackIsExecuted()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('p', 'pre-action', Parameter::VALUE_NO_VALUE),
            'Pre-action',
            function() {
                echo "Executing pre-action\n";
                return true;
            }
        );
        $helloWorld->start(array('script.php', '--pre-action'));
        $this->expectOutputString(
            "Executing pre-action\n"
            . "Hello, World!\n"
        );
    }

    public function testNonBlockingParameterCallbackIsNotExecutedWhenSwitchIsNotActivated()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('p', 'pre-action', Parameter::VALUE_NO_VALUE),
            'Pre-action',
            function() {
                echo "Executing pre-action\n";
                return true;
            }
        );
        $helloWorld->start(array('script.php'));
        $this->expectOutputString("Hello, World!\n");
    }

    public function testBlockingParameterCallbackIsExecuted()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('a', 'alternate-action', Parameter::VALUE_NO_VALUE),
            'Alternate action',
            function() {
                echo "Executing alternate action\n";
                return false;
            }
        );
        $helloWorld->start(array('script.php', '--alternate-action'));
        $this->expectOutputString("Executing alternate action\n");
    }

    public function testBlockingParameterCallbackIsNotExecutedWhenSwitchIsNotActivated()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('a', 'alternate-action', Parameter::VALUE_NO_VALUE),
            'Alternate action',
            function() {
                echo "Executing alternate action\n";
                return false;
            }
        );
        $helloWorld->start(array('script.php'));
        $this->expectOutputString("Hello, World!\n");
    }

    /**
     * @expectedException Cli\Helpers\Exception\InvalidScriptParameter
     */
    public function testInvalidParameterCallback()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('v', 'value', 'defaultValue'),
            'Some value',
            function() {}
        );
    }

    public function testBlockingParameterCallbackIsExecutedEvenIfRequiredParameterIsMissing()
    {
        $helloWorld = clone $this->helloWorld;
        $helloWorld->addParameter(
            new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
            'The username'
        );
        $helloWorld->addParameter(
            new Parameter('a', 'alternate-action', Parameter::VALUE_NO_VALUE),
            'Alternate action',
            function() {
                echo "Executing alternate action\n";
                return false;
            }
        );
        $helloWorld->start(array('script.php', '--alternate-action'));
        $this->expectOutputString("Executing alternate action\n");
    }

}
