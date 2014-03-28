<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;

/**
 * Exception that occurs with Script::addParameter($parameter, $description, $callback)
 * while $parameter is not a boolean switch (VALUE_NO_VALUE).
 */
class InvalidScriptParameter extends CliHelpersException
{
    public function __construct($parameter)
    {
        parent::__construct('Invalid parameter ' . $parameter . '. Only boolean parameters can have a callback');
    }
}
