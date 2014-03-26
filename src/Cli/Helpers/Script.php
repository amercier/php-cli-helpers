<?php

namespace Cli\Helpers;

/**
 * Cli\Helpers\Script
 * ==================
 *
 * Utility class to create scripts.
 *
 * Example
 * -------
 *
 * Let's create a script hello.php
 *
 * ```php
 * #!/usr/bin/env php
 * <?php
 *
 * require_once 'vendor/autoload.php';
 *
 * Cli\Helpers\Script::create()
 *     ->setName('Hello')
 *     ->setVersion('1.0')
 *     ->setDescription('Say hello to the world or to a particular person')
 *     ->setCopyright('Copyright (c) Orange ECV 2013')
 *     ->addParameter(new Parameter('n', 'name'   , 'World'                  ), 'Set the name of the person to greet')
 *     ->addParameter(new Parameter('V', 'verbose', Parameter::VALUE_NO_VALUE), 'Increase verbosity')
 *     ->setProgram(function($parameters) {
 *         echo 'Hello, ' . $parameters['name'];
 *         if ($parameters['verbose']) {
 *             echo ' Nice to see you again :)';
 *         }
 *         echo "\n";
 *     })
 *     ->start();
 * ```
 *
 * $ hello-world.php
 * Hello, World!
 *
 * $ hello-world.php -n Alex
 * Hello, Alex!
 *
 * $ hello-world.php --verbose
 * Hello, World! Nice to see you again :)
 *
 * $ hello-world.php --version
 * Hello v1.0
 * Copyright (c) Purple DBU 2014
 *
 * $ hello-world.php --help
 * Usage: hello-world.php [OPTIONS]
 *
 * Say hello to the world or to a particular person.
 *
 *   -n, --name NAME       Set the name of the person to greet
 *   -V, --verbose         Increase verbosity
 *   -h, --help            Display this help and exit.
 *   -v, --version         Output version information and exit.
 *
 * Hello
 * Copyright (c) Orange ECV 2013
 */
class Script
{
    protected $name;
    protected $version;
    protected $description;
    protected $copyright;
    protected $parameters = array();
    protected $parameterDescriptions = array();
    protected $program;
    protected $captureExceptions = true;

    public static function create()
    {
        return new self();
    }

    public function __construct()
    {
        $this->addParameter(
            new Parameter('h', 'help', Parameter::VALUE_NO_VALUE),
            'Display this help and exit.',
            function ($parameters) {

            }
        );
        $this->addParameter(
            new Parameter('v', 'version', Parameter::VALUE_NO_VALUE),
            'Output version information and exit.',
            function ($parameters) {

            }
        );
    }

    protected function checkProperties()
    {
        if (!isset($this->name)) {
            throw new Exception\MissingScriptParameter('name');
        }
        if (!isset($this->version)) {
            throw new Exception\MissingScriptParameter('version');
        }
        if (!isset($this->description)) {
            throw new Exception\MissingScriptParameter('description');
        }
        if (!isset($this->program)) {
            throw new Exception\MissingScriptParameter('program');
        }
    }

    protected function initOptions($arguments = null)
    {
        return Parameter::getFromCommandLine($this->parameters, $arguments);
    }

    /**
     * Run the program defined with #setProgram().
     *
     * If an exception is thrown during execution of the program, the error
     * message is displayed on STDERR, and the script exits with return code 0.
     * To prevent catching the exception, use #setCaptureExceptions(false).
     *
     * @param  array  $parameters  The script parameters
     * @return mixed            Returns whatever the program returns
     */
    protected function run($parameters)
    {
        $program = $this->program;
        if ($this->captureExceptions) {
            return $program($parameters);
        }

        try {
            return $program($parameters);
        } catch (\Exception $e) {
            fwrite(STDERR, $e->getMessage() . "\n");
            exit(1);
        }
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
        return $this;
    }

    public function setProgram($program)
    {
        $this->program = $program;
        return $this;
    }

    public function addParameter($parameter, $description)
    {

        // Check whether a parameter with the same switch already exists
        foreach ($this->parameters as $p) {
            if ($p->getShort() === $parameter->getShort()) {
                throw new Exception\DuplicateScriptParameter($p->getShort());
            }
            if ($p->getLong() === $parameter->getLong()) {
                throw new Exception\DuplicateScriptParameter($p->getLong());
            }
        }

        $id = $parameter->getLong();
        $this->parameters[$id] = $parameter;
        $this->parameterDescriptions[$id] = $description;

        return $this;
    }

    /**
     * Starts the program using either the script arguments, or custom ones.
     *
     * @param  array  $arguments  Arguments to pass to the script (optional).
     *                            When null, the script uses the command-line
     *                            arguments (global $argv).
     * @return mixed              Returns whatever the program returns
     */
    public function start($arguments = null)
    {
        $this->checkProperties();
        $parameters = $this->initOptions($arguments);
        return $this->run($parameters);
    }
}
