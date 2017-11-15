<?php

use PHPUnit\Framework\TestCase;

abstract class AbstractCliScriptTestCase extends TestCase
{
    protected function runCommand($command, $stdinData = '', $cwd = null, $env = null)
    {
        $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin
               1 => array("pipe", "w"),  // stdout
               2 => array("pipe", "w"),  // stderr
            );

        $pipes = array();
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

        return array(
                $stdout . '\n' == $stderr ? '' : $stdout,
                $stderr,
                $returnValue
            );
    }

    protected function normalizeExpectedOutput($output)
    {
        if ( !is_string($output) ) {
            ob_start();
            echo json_encode($output, JSON_PRETTY_PRINT) . "\n";
            $output = ob_get_clean();
        }
        return $output;
    }

    protected function assertScriptOutput($command, $expectedStdout, $expectedStderr = '', $expectedReturnValue = 0, $stdinData = '', $cwd = null, $env = null)
    {
        $output = $this->runCommand($command, $stdinData, $cwd, $env);
        $stdout = $output[0];
        $stderr = $output[1];
        $returnValue = $output[2];

        $this->assertEquals($this->normalizeExpectedOutput($expectedStderr), $stderr);
        $this->assertEquals($this->normalizeExpectedOutput($expectedStdout), $stdout);
        $this->assertEquals($returnValue, $expectedReturnValue);
    }

    protected function assertScriptOutputRegex($command, $expectedStdoutPattern, $expectedStderrPattern = '//s', $expectedReturnValuePattern = '/0/', $stdinData = '', $cwd = null, $env = null)
    {
        $output = $this->runCommand($command, $stdinData, $cwd, $env);
        $stdout = $output[0];
        $stderr = $output[1];
        $returnValue = $output[2];

        $this->assertRegExp($expectedStderrPattern, $stderr);
        $this->assertRegExp($expectedStdoutPattern, $stdout);
        $this->assertRegExp($expectedReturnValuePattern, '' . $returnValue);
    }

    protected function assertScriptOutputStartsWith($command, $expectedStdout, $expectedStderr = '', $expectedReturnValue = 0, $stdinData = '', $cwd = null, $env = null)
    {
        return $this->assertScriptOutputRegex(
                $command,
                '/' . preg_quote($expectedStdout, '/') . '.*/s',
                '/' . preg_quote($expectedStderr, '/') . '.*/s',
                '/' . preg_quote($expectedReturnValue, '/') . '.*/s',
                $stdinData,
                $cwd,
                $env
            );
    }

    protected function assertScriptOutputEndsWith($command, $expectedStdout, $expectedStderr = '', $expectedReturnValue = 0, $stdinData = '', $cwd = null, $env = null)
    {
        return $this->assertScriptOutputRegex(
                $command,
                '/.*' . preg_quote($expectedStdout, '/') . '/s',
                '/.*' . preg_quote($expectedStderr, '/') . '/s',
                '/.*' . preg_quote($expectedReturnValue, '/') . '/s',
                $stdinData,
                $cwd,
                $env
            );
    }

    protected function assertScriptOutputContains($command, $expectedStdout, $expectedStderr = '', $expectedReturnValue = 0, $stdinData = '', $cwd = null, $env = null)
    {
        return $this->assertScriptOutputRegex(
                $command,
                '/.*' . preg_quote($expectedStdout, '/') . '.*/s',
                '/.*' . preg_quote($expectedStderr, '/') . '.*/s',
                '/.*' . preg_quote($expectedReturnValue, '/') . '.*/s',
                $stdinData,
                $cwd,
                $env
            );
    }
}
