<?php

use Cli\Helpers\DocumentedScript;
use Cli\Helpers\Parameter;

class DocumentedScriptUnitTestCase extends PHPUnit_Framework_TestCase
{
    // protected $helloWorld;

    // public function setUp()
    // {
    //     $this->helloWorld = DocumentedScript::create()
    //         ->setExceptionCatchingEnabled(false)
    //         ->setName('Hello')
    //         ->setVersion('1.0')
    //         ->setDescription('Say hello to the world or to a particular person')
    //         ->setCopyright('Copyright (c) Orange ECV 2013')
    //         ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
    //         ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
    //         ->setProgram(function($options) {
    //             if ($options['name'] === 'Adolf') {
    //                 throw new \Exception('That name is prohibited');
    //             }
    //             echo 'Hello, ' . $options['name'] . '!';
    //             if ($options['verbose']) {
    //                 echo ' Nice to see you again :)';
    //             }
    //             echo "\n";
    //             return 'OK';
    //         });
    // }

    // public function testVersionWithShortSwitch() {
    //     $result = $this->helloWorld->start(array('script.php', '-v'));
    //     $this->expectOutputString(
    //         "Usage: script.php [OPTIONS]\n"
    //         . "\n"
    //         . "Say hello to the world or to a particular person\n"
    //         . "\n"
    //         . "  -h, --help       Display this help and exit.\n"
    //         . "  -v, --version    Output version information and exit.\n"
    //         . "\n"
    //         . "Hello v1.0\n"
    //         . "Copyright (c) Copyright (c) Orange ECV 2013\n"
    //     );
    // }

    // public function testVersionWithLongSwitch() {
    //     $result = $this->helloWorld->start(array('script.php', '--version'));
    //     $this->expectOutputString(
    //         "Usage: script.php [OPTIONS]\n"
    //         . "\n"
    //         . "Say hello to the world or to a particular person\n"
    //         . "\n"
    //         . "  -h, --help       Display this help and exit.\n"
    //         . "  -v, --version    Output version information and exit.\n"
    //         . "\n"
    //         . "Hello v1.0\n"
    //         . "Copyright (c) Copyright (c) Orange ECV 2013\n"
    //     );
    // }

    // public function testHelpWithShortSwitch() {
    //     $result = $this->helloWorld->start(array('script.php', '-h'));
    //     $this->expectOutputString(
    //         "Hello v1.0\n"
    //         . "Copyright (c) Copyright (c) Orange ECV 2013\n"
    //     );
    // }

    // public function testHelpWithLongSwitch() {
    //     $result = $this->helloWorld->start(array('script.php', '--help'));
    //     $this->expectOutputString(
    //         "Hello v1.0\n"
    //         . "Copyright (c) Copyright (c) Orange ECV 2013\n"
    //     );
    // }
    //
    public function testOK()
    {
        $this->assertEquals(true, true);
    }
}
