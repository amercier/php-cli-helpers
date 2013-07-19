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
 *         'host'     => new Parameter('h', 'host'    , Parameter::VALUE_REQUIRED),
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
 * `Cli\Helpers\Exception\MissingCommandLineParameter` exception because the
 * options '-p/--password' is required.
 *
 * Executing this with `my-script.php -u myname -p` will result in a
 * `Cli\Helpers\Exception\MissingCommandLineParameterValue` exception because
 * the options '-p/--password' requires a value.
 *
 * Executing this with `my-script.php -u myname -p password --passsword
 * password` will result in a
 * `Cli\Helpers\Exception\ConflictingCommandLineParameters` exception because
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

    public function __construct($short, $long, $defaultValue = VALUE_REQUIRED)
    {
        $this->short = $short;
        $this->long = $long;
        $this->defaultValue = $defaultValue;
    }

    protected function getOptModifier() {
        if ( $this->defaultValue === VALUE_REQUIRED ) {
            return ':';
        }
        else if ( $this->defaultValue === VALUE_NO_VALUE ) {
            return '';
        }
        else {
            return ':';
        }
    }

    protected function getShortOpt() {
        return $this->short . $this->getOptModifier();
    }

    protected function getLongOpt() {
        return $this->long . $this->getOptModifier();
    }

    public static function getFromCommandLine( array $parameters )
    {
        $options = array();

        $rawOptions = getopt(
                implode('', array_map(function($p) { return $p->getShortOpt(); }, $parameters)),
                array_map(function($p) { return $p->getLongOpt(); }, $parameters)
            );

        return array_map(
                function ($parameter) {

                    if ( $rawOptions[$parameter->$short] !== false && $rawOptions[$parameter->$long] !== false ) {
                        throw new Exception\ConflictingCommandLineParameters( $parameter, $rawOptions[$parameter->$short], $rawOptions[$parameter->$long] );
                    }

                    if ( $this->defaultValue === VALUE_REQUIRED ) {
                        if ($rawOptions[$parameter->$short] !== false) {

                        }
                        else if ($rawOptions[$parameter->$short] !== false) {

                        }
                        else {

                        }
                    }
                    else if ($this->defaultValue === VALUE_NO_VALUE) {
                    }
                    else {
                        return $this->defaultValue;
                    }
                },
                $parameters
            );
    }
}
