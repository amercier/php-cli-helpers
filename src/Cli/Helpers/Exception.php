<?php

namespace Cli\Helpers;

/**
 * Abstract exception class for this project. Parent class of all other
 * exceptions.
 *
 * All Cli\Helpers\Exception\* exception classes extend this class. This makes
 * catching a bit easier as you can catch all Cli\Helpers\Exception\* exceptions
 * with a single:
 *
 *     catch (Cli\Helpers\Exception\AbstractException $e) {
 *         ...
 *     }
 *
 * instead of having to catch all exceptions individually.
 */
abstract class Exception extends \Exception
{
}
