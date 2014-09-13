<?php

require_once dirname(__FILE__) . '/AbstractScriptTestCase.php';

class ScriptIntegrationTestCase extends AbstractCliScriptTestCase
{
    const SCRIPT = 'php data/test-script.php';

    public function testMissingParameter()
    {
        $this->assertScriptOutput(
            self::SCRIPT,
            '',
            "Missing parameter -u/--username in command \"php data/test-script.php\"\n",
            1
        );
    }

    public function testMissingParameterValue()
    {
        $this->assertScriptOutput(
            self::SCRIPT . ' -u',
            '',
            "Missing value for parameter -u/--username in command \"php data/test-script.php -u\"\n",
            1
        );
    }

    public function testConflictingParameters()
    {
        $this->assertScriptOutput(
            self::SCRIPT . ' -u myname --username myname',
            '',
            "Conflicting parameters -u and --username in command \"php data/test-script.php -u myname --username myname\"\n",
            1
        );
    }
}
