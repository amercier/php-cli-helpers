<?php

namespace Cli\Helpers;

class Job
{
    protected $message;
    protected $function;
    protected $debug;

    public function __construct($message, $function, $debug = false)
    {
        $this->message = $message;
        $this->function = $function;
        $this->debug = $debug;
    }

    public function start()
    {
        echo $this->message . '... ';
        try {
            $function = $this->function;
            $result = $function();
        } catch (\Exception $e) {
            echo 'NOK - ' . $e->getMessage() . "\n";
            if ($this->debug) {
                echo $e->getTraceAsString() . "\n";
            }
            return false;
        }
        echo "OK\n";
        return $result;
    }

    public static function run($message, $function, $debug = false)
    {
        $job = new Job($message, $function, $debug);
        return $job->start();
    }
}
