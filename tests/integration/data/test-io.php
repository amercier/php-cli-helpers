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

use Cli\Helpers\IO;

$name = $argv[1];
$values = array_slice($argv, 2);

$response = IO::form($name, $values);

echo "\nResponse: ";
echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
