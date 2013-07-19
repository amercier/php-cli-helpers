<?php

namespace Cli\Helpers\Exception;

/**
 * Abstract exception class for this project.
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
abstract class AbstractException extends Exception
{
}
