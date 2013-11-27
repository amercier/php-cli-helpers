<?php

namespace Cli\Helpers;

/**
 * Cli\Helpers\Job
 * =====================
 *
 * Utility class to run a job and catch exceptions.
 *
 * Usage
 * -----
 *
 * On successful jobs:
 * ```php
 * \Cli\Helpers\Job::run('Doing awesome stuff', function() {
 *     ...  // awesome stuff
 * });
 * ```
 * ```
 * Doing awesome stuff... OK
 * ```
 *
 * On unsuccessful jobs:
 * ```php
 * \Cli\Helpers\Job::run('Fighting Chuck Norris', function() {
 *     ...  // throws a RoundHouseKickException('You've received a round-house kick', 'face')
 * });
 * ```
 * ```
 * Fighting Chuck Norris... NOK - You've received a round-house kick in the face
 * ```
 */
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
