<?php

require_once dirname(__FILE__) . '/AbstractScriptTestCase.php';

class ParameterIntegrationTestCase extends AbstractCliScriptTestCase
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

    public function testConflictingRequiredParameter()
    {
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' -u myname -p mypassword --username myname',
            '',
            'Conflicting parameters -u and --username in command "php data/test-parameters.php -u myname -p mypassword --username myname"',
            255
        );
    }

    public function testConflictingOptionalParameter()
    {
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com --host myserver.example.com',
            '',
            'Conflicting parameters -h and --host in command "php data/test-parameters.php -u myname -p mypassword -h myserver.example.com --host myserver.example.com"',
            255
        );
    }

    public function testConflictingSwitchParameter()
    {
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' -u myname -p mypassword -v --verbose',
            '',
            'Conflicting parameters -v and --verbose in command "php data/test-parameters.php -u myname -p mypassword -v --verbose"',
            255
        );
    }

    public function testMissingRequiredParameter()
    {
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . '',
            '',
            'Missing parameter -u/--username in command "php data/test-parameters.php"',
            255
        );
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' -u myname',
            '',
            'Missing parameter -p/--password in command "php data/test-parameters.php -u myname"',
            255
        );
    }

    public function testMissingParameterValue()
    {
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' -u',
            '',
            'Missing value for parameter -u/--username in command "php data/test-parameters.php -u"',
            255
        );
        $this->assertScriptOutputStartsWith(
            self::SCRIPT . ' --username',
            '',
            'Missing value for parameter -u/--username in command "php data/test-parameters.php --username"',
            255
        );
    }
}
