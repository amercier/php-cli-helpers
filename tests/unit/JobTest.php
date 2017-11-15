<?php

use PHPUnit\Framework\TestCase;

use Cli\Helpers\Job;

class JobUnitTestCase extends TestCase
{
    public function testSuccessfulJob()
    {
        ob_start();
        $return = Job::run('Running successful job', function() { return ':D'; });
        $output = ob_get_clean();

        $this->assertEquals(':D', $return);
        $this->assertEquals("Running successful job... OK\n", $output);
    }

    public function testUnuccessfulJob()
    {
        ob_start();
        $return = Job::run('Running unsuccessful job', function() {
            throw new Exception(":'(");
        });
        $output = ob_get_clean();

        $this->assertEquals(false, $return);
        $this->assertEquals("Running unsuccessful job... NOK - :'(\n", $output);
    }

    public function testUnuccessfulJobVerbose()
    {
        ob_start();
        $return = Job::run('Running unsuccessful job', function() {
            throw new Exception(":'(");
        }, array(), true);
        $output = ob_get_clean();

        $this->assertEquals(false, $return);
        $this->assertStringStartsWith("Running unsuccessful job... NOK - :'(\n", $output);
        foreach(array('JobTest.php(32)', 'Job.php(38)', 'Job.php(23)') as $fragment) {
            $this->assertRegExp('/.*' . preg_quote($fragment, '/') . '.*/', $fragment);
        }
    }

    public function testFunctionArguments()
    {
        $a = 1;
        $b = 2;

        ob_start();
        $return = Job::run(
            'Running job with arguments',
            function($a = null, $b = null) {
                return array('a' => $a, 'b' => $b);
            },
            array($a, $b)
        );
        ob_end_clean();

        $this->assertTrue(is_array($return));
        $this->assertTrue(array_key_exists('a', $return));
        $this->assertTrue(array_key_exists('b', $return));
        $this->assertEquals(1, $return['a']);
        $this->assertEquals(2, $return['b']);
    }
}
