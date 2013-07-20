<?php

require_once 'bootstrap.php';

class ParameterTestCase extends AbstractCliScriptTestCase
{
    const SCRIPT = 'php data/test-parameters.php';

    public function testShortRequiredParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword', array(
                'host' => '127.0.0.1',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => false
            ));
    }

    public function testLongRequiredParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' --username myname --password mypassword', array(
                'host' => '127.0.0.1',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => false
            ));
    }

    public function testShortOptionalParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com', array(
                'host' => 'myserver.example.com',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => false
            ));
    }

    public function testLongOptionalParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword --host myserver.example.com', array(
                'host' => 'myserver.example.com',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => false
            ));
    }

    public function testShortSwitchParameters()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword -v', array(
                'host' => '127.0.0.1',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => true
            ));
    }

    public function testLongSwitchParameters()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword --verbose', array(
                'host' => '127.0.0.1',
                'username' => 'myname',
                'password' => 'mypassword',
                'verbose' => true
            ));
    }
}
