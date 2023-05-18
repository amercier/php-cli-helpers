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
 *
 * You can also add parameters to the function:
 *
 * ```php
 * \Cli\Helpers\Job::run(
 *     'Doing awesome stuff',
 *     function($a, $b) {
 *         $a; // => 1337;
 *         $a; // => 'good luck, im behind 7 firewalls';
 *     },
 *     array(1337, 'im behind 7 firewalls')
 * });
 * ```
 */
class Job
{
    protected $message;
    protected $function;
    protected $debug;
    protected $arguments;
    
    public function __construct($message, $function, $arguments = array(), $debug = false)
    {
        $this->message = $message;
        $this->function = $function;
        $this->arguments = $arguments;
        $this->debug = $debug;
    }

    public function start()
    {
        echo $this->message . '... ';
        try {
            $result = call_user_func_array($this->function, $this->arguments);
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

    public static function run($message, $function, $arguments = array(), $debug = false)
    {
        $job = new Job($message, $function, $arguments, $debug);
        return $job->start();
    }
}
