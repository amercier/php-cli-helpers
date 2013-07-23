<?php

use Cli\Helpers\Parameter;

class ParameterTestCase extends PHPUnit_Framework_TestCase
{
    const SCRIPT = 'data/test-parameters.php';

    protected static $parameters;

    public static function setUpBeforeClass()
    {
        /*
        foreach( $GLOBALS['argv'] as $key => $arg )
        {
            unset( $GLOBALS['argv'][$key] );
        }
        */

        self::$parameters = array(
                'host'     => new Parameter('h', 'host'    , '127.0.0.1'),
                'username' => new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
                'password' => new Parameter('p', 'password', Parameter::VALUE_REQUIRED),
                'verbose'  => new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE),
            );
    }

    public function testShortRequiredParameter()
    {
        $this->assertEquals(
                array(
                    'host' => '127.0.0.1',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => false
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' -u myname -p mypassword')
                )
            );
    }

    public function testLongRequiredParameter()
    {
        $this->assertEquals(
                array(
                    'host' => '127.0.0.1',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => false
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' --username myname --password mypassword')
                )
            );
    }

    public function testShortOptionalParameter()
    {
        $this->assertEquals(
                array(
                    'host' => 'myserver.example.com',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => false
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com')
                )
            );
    }

    public function testLongOptionalParameter()
    {
        $this->assertEquals(
                array(
                    'host' => 'myserver.example.com',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => false
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' -u myname -p mypassword --host myserver.example.com')
                )
            );
    }

    public function testShortSwitchParameters()
    {
        $this->assertEquals(
                array(
                    'host' => '127.0.0.1',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => true
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' -u myname -p mypassword -v')
                )
            );
    }

    public function testLongSwitchParameters()
    {
         $this->assertEquals(
                array(
                    'host' => '127.0.0.1',
                    'username' => 'myname',
                    'password' => 'mypassword',
                    'verbose' => true
                ),
                Parameter::getFromCommandLine(
                    self::$parameters,
                    explode(' ', self::SCRIPT . ' -u myname -p mypassword --verbose')
                )
            );
    }

    /*
    public function testConflictingRequiredParameter()
    {
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' -u myname -p mypassword --username myname', '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\ConflictingParameters' with message 'Conflicting parameters -u and --username in command \"php data/test-parameters.php -u myname -p mypassword --username myname\"'", 255);
    }

    public function testConflictingOptionalParameter()
    {
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com --host myserver.example.com', '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\ConflictingParameters' with message 'Conflicting parameters -h and --host in command \"php data/test-parameters.php -u myname -p mypassword -h myserver.example.com --host myserver.example.com\"'", 255);
    }

    public function testConflictingSwitchParameter()
    {
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' -u myname -p mypassword -v --verbose', '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\ConflictingParameters' with message 'Conflicting parameters -v and --verbose in command \"php data/test-parameters.php -u myname -p mypassword -v --verbose\"'", 255);
    }

    public function testMissingRequiredParameter()
    {
        $this->assertScriptOutputStartsWith(self::SCRIPT . ''          , '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\MissingRequiredParameter' with message 'Missing parameter -u/--username in command \"php data/test-parameters.php\"'", 255);
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' -u myname', '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\MissingRequiredParameter' with message 'Missing parameter -p/--password in command \"php data/test-parameters.php -u myname\"'", 255);
    }

    public function testMissingParameterValue()
    {
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' -u'        , '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\MissingParameterValue' with message 'Missing value for parameter -u/--username in command \"php data/test-parameters.php -u\"'", 255);
        $this->assertScriptOutputStartsWith(self::SCRIPT . ' --username', '', "PHP Fatal error:  Uncaught exception 'Cli\\Helpers\\Exception\\MissingParameterValue' with message 'Missing value for parameter -u/--username in command \"php data/test-parameters.php --username\"'", 255);
    }
    */
}
