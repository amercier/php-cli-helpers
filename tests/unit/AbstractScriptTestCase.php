<?php

require_once '../../vendor/autoload.php';

abstract class AbstractCliScriptTestCase extends PHPUnit_Framework_TestCase
{
    protected function assertScriptOutput($command, $expectedStdout, $expectedStderr = '', $expectedReturnValue = 0, $stdinData = '', $cwd = null, $env = null)
    {
        if ( !is_string($expectedStdout) ) {
            ob_start();
            var_dump($expectedStdout);
            $expectedStdout = ob_get_clean();
        }

        if ( !is_string($expectedStderr) ) {
            ob_start();
            var_dump($expectedStderr);
            $expectedStderr = ob_get_clean();
        }

        $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin
               1 => array("pipe", "w"),  // stdout
               2 => array("pipe", "w"),  // stderr
            );

        $process = proc_open($command, $descriptorspec, $pipes, $cwd === null ? dirname(__FILE__) : $cwd, $env);
        if ( !is_resource($process) ) {
            throw new Exception('Failed to execute command "' . $command . '"');
        }

        // Write stdin data to the stdin pipe
        fwrite($pipes[0], $stdinData);
        fclose($pipes[0]);

        // Read stdout
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read stderr
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // Get return value
        $returnValue = proc_close($process);

        //$this->assertEquals($stdout . "\n- - - - - - - - - - - - - - - - - - - - -\n" . $stderr, $expectedStdout . "\n- - - - - - - - - - - - - - - - - - - - -\n" . $expectedStderr);
        $this->assertEquals($expectedStderr, $stderr);
        $this->assertEquals($expectedStdout, $stdout);
        $this->assertEquals($returnValue, $expectedReturnValue);
    }
}
