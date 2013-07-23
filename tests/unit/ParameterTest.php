<?php

use Cli\Helpers\Parameter;

class ParameterTestCase extends PHPUnit_Framework_TestCase
{
    const SCRIPT = 'data/test-parameters.php';

    protected static $parameters;

    public static function setUpBeforeClass()
    {
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

    /**
     * @expectedException Cli\Helpers\Exception\ConflictingParameters
     */
    public function testConflictingRequiredParameter()
    {
        Parameter::getFromCommandLine(
                self::$parameters,
                explode(' ', self::SCRIPT . ' -u myname -p mypassword --username myname')
            );
    }

    /**
     * @expectedException Cli\Helpers\Exception\ConflictingParameters
     */
    public function testConflictingOptionalParameter()
    {
        Parameter::getFromCommandLine(
                self::$parameters,
                explode(' ', self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com --host myserver.example.com')
            );
    }

    /**
     * @expectedException Cli\Helpers\Exception\ConflictingParameters
     */
    public function testConflictingSwitchParameter()
    {
        Parameter::getFromCommandLine(
                self::$parameters,
                explode(' ', self::SCRIPT . ' -u myname -p mypassword -v --verbose')
            );
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingRequiredParameter
     */
    public function testMissingRequiredParameter()
    {
        Parameter::getFromCommandLine(
                self::$parameters,
                explode(' ', self::SCRIPT . '')
            );
    }

    /**
     * @expectedException Cli\Helpers\Exception\MissingRequiredParameter
     */
    public function testMissingRequiredParameter2()
    {
        Parameter::getFromCommandLine(
                self::$parameters,
                explode(' ', self::SCRIPT . ' -u myname')
            );
    }

    // /**
    //  * @expectedException Cli\Helpers\Exception\MissingParameterValue
    //  */
    // public function testMissingParameterValue()
    // {
    //     Parameter::getFromCommandLine(
    //             self::$parameters,
    //             explode(' ', self::SCRIPT . ' -u')
    //         );
    // }

    // /**
    //  * @expectedException Cli\Helpers\Exception\MissingParameterValue
    //  */
    // public function testMissingParameterValue2()
    // {
    //     Parameter::getFromCommandLine(
    //             self::$parameters,
    //             explode(' ', self::SCRIPT . ' --username')
    //         );
    // }
}
