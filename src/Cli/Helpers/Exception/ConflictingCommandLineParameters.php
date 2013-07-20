<?php

namespace Cli\Helpers\Exception;

use Cli\Helpers\Exception as CliHelpersException;

/**
 * Exception that occurs with Parameter::getFromCommandLine() where both a short
 * parameter and its long equivalent are given simulteanously. Ex:
 *
 *     my-script -u amercier --username amercier
 */
class ConflictingCommandLineParameters extends CliHelpersException
{
	public function __construct($parameter)
	{
		global $argv;
		parent::__construct('Conflicting parameters -' . $parameter->getShort() . ' and --' . $parameter->getLong() . ' in command "php ' . implode(' ',$argv) . '"');
	}

}