<?php

use PHPUnit\Framework\TestCase;

use Cli\Helpers\DocumentedScript;
use Cli\Helpers\Parameter;

class DocumentedScriptUnitTestCase extends TestCase
{
    protected $helloWorld;

    public function setUp()
    {
        $this->helloWorld = new DocumentedScript();
        $this->helloWorld
            ->setExceptionCatchingEnabled(false)
            ->setName('Hello')
            ->setVersion('1.0')
            ->setDescription('Say hello to the world or to a particular person')
            ->setCopyright('Copyright (c) Alexandre Mercier 2014')
            ->addParameter(new Parameter('p', 'password', Parameter::VALUE_REQUIRED), 'The password.')
            ->addParameter(new Parameter('u', 'username', Parameter::VALUE_REQUIRED), 'The username.')
            ->addParameter(new Parameter('H', 'host'    , '127.0.0.1')              , 'The host.')
            ->setProgram(function($options) {
                echo "Hello, World!\n";
            });
    }

    public function testHelp() {
        $result = $this->helloWorld->start(array('script.php', '-h'));
        $this->expectOutputString(
            "Usage: script.php -u USERNAME -p PASSWORD [OPTIONS]\n"
            . "\n"
            . "Say hello to the world or to a particular person\n"
            . "\n"
            . "  -H, --host     HOST        The host (defaults to '127.0.0.1').\n"
            . "  -u, --username USERNAME    The username.\n"
            . "  -p, --password PASSWORD    The password.\n"
            . "  -h, --help                 Display this help and exit.\n"
            . "  -V, --version              Output version information and exit.\n"
            . "\n"
            . "Hello v1.0\n"
            . "Copyright (c) Alexandre Mercier 2014\n"
        );
    }

    public function testVersion() {
        $result = $this->helloWorld->start(array('script.php', '-V'));
        $this->expectOutputString(
            "Hello v1.0\n"
            . "Copyright (c) Alexandre Mercier 2014\n"
        );
    }
}
