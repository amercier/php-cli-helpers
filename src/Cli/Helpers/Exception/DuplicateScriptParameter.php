<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;
use Cli\Helpers\Parameter;

/**
 * Exception that occurs with Script::run() where no <type> is set. Use
 * Script::set<Type>(<value>) to set a <type>.
 */
class DuplicateScriptParameter extends CliHelpersException
{
    public function __construct($switch, Parameter $oldParameter)
    {
        parent::__construct('Switch ' . $switch . ' is already defined by ' . $oldParameter);
    }
}
