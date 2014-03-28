<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;

/**
 * Exception that occurs with Script::run() where no <type> is set. Use
 * Script::set<Type>(<value>) to set a <type>.
 */
class DuplicateScriptParameter extends CliHelpersException
{
    public function __construct($type)
    {
        parent::__construct('Switch ' . $type . ' is already defined');
    }
}
