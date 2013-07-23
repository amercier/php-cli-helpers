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

    protected function getOptModifier() {
        if ( $this->defaultValue === self::VALUE_REQUIRED ) {
            return ':';
        }
        else if ( $this->defaultValue === self::VALUE_NO_VALUE ) {
            return '';
        }
        else {
            return ':';
        }
    }

    public function getShort()
    {
        return $this->short;
    }

    public function getLong()
    {
        return $this->long;
    }

    public function getShortOpt()
    {
        return $this->short . $this->getOptModifier();
    }

    public function getLongOpt()
    {
        return $this->long . $this->getOptModifier();
    }

    public function getValue( $rawOptions )
    {
        // Prevent short and long options simultaneously
        if ( array_key_exists($this->getShort(), $rawOptions) && array_key_exists($this->getLong(), $rawOptions) ) {
            throw new Exception\ConflictingParameters(
                    $this,
                    $rawOptions[$this->getShort()],
                    $rawOptions[$this->getLong()]
                );
        }

        // If it's a switch parameter (ex: -v/--verbose) return true if it was given, false otherwise
        if ($this->defaultValue === self::VALUE_NO_VALUE) {
            return array_key_exists($this->getShort(), $rawOptions) || array_key_exists($this->getLong(), $rawOptions);
        }


        // If it's a value parameter (ex: -h/--host 127.0.0.1)...

        // Return the value if it exists
        if (  array_key_exists($this->getShort(), $rawOptions) ) {
            return $rawOptions[ $this->getShort() ];
        }

        if ( array_key_exists($this->getLong(), $rawOptions) ) {
            return $rawOptions[ $this->getLong() ];
        }

        // No value
        if ($this->defaultValue === self::VALUE_REQUIRED) { // required
            global $argv;
            throw in_array('-' . $this->getShort(), $argv) || in_array('--' . $this->getLong(), $argv)
                ? new Exception\MissingParameterValue( $this )
                : new Exception\MissingRequiredParameter( $this );
        }
        else { // default value exists
            return $this->defaultValue;
        }
    }

    public static function getFromCommandLine( array $parameters, $args = null )
    {
        global $argv;
        $options = array();

        if ($args === null) {
            $rawOptions = getopt(
                    implode('', array_map(function($p) { return $p->getShortOpt(); }, $parameters)),
                    array_map(function($p) { return $p->getLongOpt(); }, $parameters)
                );
        }
        else {
            $allOptions = \Console_Getopt::getopt(
                    $args,
                    implode('', array_map(function($p) { return $p->getShortOpt(); }, $parameters)),
                    array_map(function($p) { return str_replace(':','=',$p->getLongOpt()); }, $parameters)
                );

            if( $allOptions instanceof \PEAR_Error ) {
                throw new \Exception( $allOptions->getMessage() );
            }

            $rawOptions = array();
            foreach( $allOptions[0] as $parsedOption ) {
                $rawOptions[ preg_replace('/^--/','',$parsedOption[0]) ] = $parsedOption[1];
            }
        }

        // echo 'getopt("' . implode('', array_map(function($p) { return $p->getShortOpt(); }, $parameters)) . '") = ';
        // print_r( $rawOptions );
        // echo "---------------------\n";

        $options = array();
        foreach( $parameters as $key => $parameter ) {
            $options[ $key ] = $parameter->getValue( $rawOptions );
        }

        return $options;
    }
}
