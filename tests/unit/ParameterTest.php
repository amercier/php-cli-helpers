<?php

require_once 'bootstrap.php';

class ParameterTestCase extends AbstractCliScriptTestCase
{
    const SCRIPT = 'php data/test-parameters.php';

    public function testShortRequiredParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'127.0.0.1\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => false,',
                '}',
            )));
    }

    public function testLongRequiredParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' --username myname --password mypassword', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'127.0.0.1\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => false,',
                '}',
            )));
    }

    public function testShortOptionalParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword -h myserver.example.com', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'myserver.example.com\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => false,',
                '}',
            )));
    }

    public function testLongOptionalParameter()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword --host myserver.example.com', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'myserver.example.com\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => false,',
                '}',
            )));
    }

    public function testShortSwitchParameters()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword -v', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'127.0.0.1\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => true,',
                '}',
            )));
    }

    public function testLongSwitchParameters()
    {
        $this->assertScriptOutput(self::SCRIPT . ' -u myname -p mypassword --verbose', implode("\n", array(
                'array(4) {',
                '    \'host\'     => \'127.0.0.1\',',
                '    \'username\' => \'myusername\',',
                '    \'password\' => \'mypassword\',',
                '    \'verbose\'  => true,',
                '}',
            )));
    }
}
