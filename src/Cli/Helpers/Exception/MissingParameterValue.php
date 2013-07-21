<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;

/**
 * Exception that occurs with Parameter::getFromCommandLine() where a required
 * parameter is given with no value. Ex:
 *
 *     my-script.php -u
 */
class MissingParameterValue extends CliHelpersException
{
    public function __construct($parameter)
    {
        global $argv;
        parent::__construct('Missing value for parameter -' . $parameter->getShort() . '/--' . $parameter->getLong() . ' in command "php ' . implode(' ',$argv) . '"');
    }

}
