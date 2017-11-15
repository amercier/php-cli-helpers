#!/usr/bin/env php
<?php
/**
 * test-parameters.php - test script for Cli\Helpers\Parameter
 *
 *     -u USERNAME, --username USERNAME   User name (required)
 *     -p PASSWORD, --password PASSWORD   Password (required)
 *     -u HOST, --host HOST               Host (optional, defaults to 127.0.0.1)
 *     -v, --verbose                      Verbose switch (optional)
 */

ini_set('display_errors', 'stderr');
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Cli\Helpers\Parameter;

$options = Parameter::getFromCommandLine(array(
        'host'     => new Parameter('h', 'host'    , '127.0.0.1'),
        'username' => new Parameter('u', 'username', Parameter::VALUE_REQUIRED),
        'password' => new Parameter('p', 'password', Parameter::VALUE_REQUIRED),
        'verbose'  => new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE),
    ));
echo json_encode($options, JSON_PRETTY_PRINT) . "\n";
