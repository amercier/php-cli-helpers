<?php

use Cli\Helpers\Job;

class JobUnitTestCase extends PHPUnit_Framework_TestCase
{
    public function testSuccessfulJob()
    {
        ob_start();
        $return = Job::run('Running successful job', function() { return ':D'; });
        $output = ob_get_clean();
        $this->assertEquals($return, ':D');
        $this->assertEquals("Running successful job... OK\n", $output);
    }

    public function testUnuccessfulJob()
    {
        ob_start();
        $return = Job::run('Running unsuccessful job', function() {
            throw new Exception(":'(");
        });
        $output = ob_get_clean();
        $this->assertEquals($return, false);
        $this->assertEquals("Running unsuccessful job... NOK - :'(\n", $output);
    }

    public function testUnuccessfulJobVerbose()
    {
        ob_start();
        $return = Job::run('Running unsuccessful job', function() {
            throw new Exception(":'(");
        }, true);
        $output = ob_get_clean();
        $this->assertEquals($return, false);
        $this->assertStringStartsWith("Running unsuccessful job... NOK - :'(\n", $output);
        foreach(array('JobTest.php(32)', 'Job.php(38)', 'Job.php(23)') as $fragment) {
            $this->assertRegExp('/.*' . preg_quote($fragment, '/') . '.*/', $fragment);
        }
    }
}
