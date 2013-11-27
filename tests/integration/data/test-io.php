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

require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Cli\Helpers\IO as IO;

$name = $argv[1];
$values = array_slice($argv, 2);

$response = IO::form($name, $values);

echo "\nResponse: ";
var_dump($response);
