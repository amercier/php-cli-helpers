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
}
