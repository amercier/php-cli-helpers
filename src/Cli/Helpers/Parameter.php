<?php

namespace Cli\Helpers;

/**
 * Cli\Helpers\Parameter
 * =====================
 *
 * Utility class to handle command-line parameters.
 *
 * Example
 * -------
 *
 * Considering the following command
 *
 *     my-script.php -u myname -p mypassord
 *     my-script.php --username myname --password mypassord
 *     my-script.php -u myname -p
 *     my-script.php -u myname -p mypassord -v
 *     my-script.php -u myname -p mypassord --verbose
 *     my-script.php -u myname -p mypassord -h server.example.com
 *     my-script.php -u myname -p mypassord --host server.example.com
 *
 * Where:
 *
 *     -u USERNAME, --username USERNAME   User name (required)
 *     -p PASSWORD, --password PASSWORD   Password (required)
 *     -u HOST, --host HOST               Host (optional, defaults to 127.0.0.1)
 *     -v, --verbose                      Verbose switch (optional)
 *
 * Usage:
 *
 * ```php
 * use Cli\Helpers\Parameter as Parameter;
 * $options = Parameter::getFromCommandLine(array(
 *         'host'     => new Parameter('h', 'host'    , '127.0.0.1'),
 *         'username' => new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
 *         'password' => new Parameter('p', 'password', Parameter::VALUE_REQUIRED),
 *         'verbose'  => new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE),
 *     ));
 * var_dump( $options );
 * ```
 *
 * Executing this with `my-script.php -u myname -p mypassord` or `my-script.php
 * --username=myname --password=mypassord` will display:
 *
 * ```php
 * array(4) {
 *     'host'     => '127.0.0.1',
 *     'username' => 'myusername',
 *     'password' => 'mypassword',
 *     'verbose'  => false,
 * }
 * ```
 *
 * Executing this with `my-script.php -u myname -p mypassord -h
 * server.example.com -v` will display:
 *
 * ```php
 * array(4) {
 *     'host'     => 'server.example.com',
 *     'username' => 'myusername',
 *     'password' => 'mypassword',
 *     'verbose'  => true
 * }
 * ```
 *
 * Executing this with `my-script.php -u myname` will result in a
 * `Cli\Helpers\Exception\MissingRequiredParameter` exception because the
 * options '-p/--password' is required.
 *
 * Executing this with `my-script.php -u myname -p` will result in a
 * `Cli\Helpers\Exception\MissingParameterValue` exception because
 * the options '-p/--password' requires a value.
 *
 * Executing this with `my-script.php -u myname -p password --passsword
 * password` will result in a
 * `Cli\Helpers\Exception\ConflictingParameters` exception because
 * the options '-p/--password' cannot be used with the short and long switches
 * cannot be used simultaneously.
 */
class Parameter
{
    const VALUE_REQUIRED = 'ac67ede5a84eb5a1add7ff4440e9a485'; // md5('required');
    const VALUE_NO_VALUE = 'e3cd7dd8951e151fb2e06104940064a0'; // md5('no value');

    protected $short;
    protected $long;
    protected $defaultValue;

    public function __construct($short, $long, $defaultValue = self::VALUE_REQUIRED)
    {
        $this->short = $short;
        $this->long = $long;
        $this->defaultValue = $defaultValue;
    }

    public function getShort()
    {
        return $this->short;
    }

    public function getLong()
    {
        return $this->long;
    }

    public function getShortSwitch()
    {
        return '-' . $this->getShort();
    }

    public function getLongSwitch()
    {
        return '--' . $this->getLong();
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function getValue($arguments)
    {
        // Parse arguments
        $index = -1;
        foreach ($arguments as $i => $value) {
            if ($i !== 0 && ($value === $this->getShortSwitch() || $value === $this->getLongSwitch())) {
                // Prevent short and long options simultaneously
                if ($index !== -1) {
                    throw new Exception\ConflictingParameters($this, $arguments);
                }

                $index = $i;
            }
        }

        // If it's a switch parameter (ex: -v/--verbose) return true if it was given, false otherwise
        if ($this->defaultValue === self::VALUE_NO_VALUE) {
            return $index !== -1;
        }

        // If it's a value parameter (ex: -h/--host 127.0.0.1)...

        // Return the value if it exists
        if ($index  !== -1 && $index < count($arguments) - 1) {
            return $arguments[ $index + 1 ];
        }

        // No value
        if ($this->defaultValue === self::VALUE_REQUIRED) {
            // required
            throw $index === -1
                ? new Exception\MissingRequiredParameter($this, $arguments)
                : new Exception\MissingParameterValue($this, $arguments);
        } else {
            // default value exists
            return $this->defaultValue;
        }
    }

    public static function getFromCommandLine(array $parameters, $arguments = null)
    {
        global $argv;
        $options = array();
        foreach ($parameters as $key => $parameter) {
            $options[ $key ] = $parameter->getValue($arguments === null ? $argv : $arguments);
        }

        return $options;
    }

    public function __toString()
    {
        return $this->getShortSwitch() . ', ' . $this->getLongSwitch();
    }
}
