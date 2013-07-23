<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;

/**
 * Exception that occurs with Parameter::getFromCommandLine() where a required
 * parameter is missing. Ex:
 *
 *     my-script.php                (-u/--username missing)
 */
class MissingRequiredParameter extends CliHelpersException
{
    public function __construct($parameter, $arguments)
    {
        global $argv;
        parent::__construct('Missing parameter -' . $parameter->getShort() . '/--' . $parameter->getLong() . ' in command "php ' . implode(' ', $arguments) . '"');
    }

}
