#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Cli\Helpers\Script;
use Cli\Helpers\Parameter;

$script = new Script();
$script
    ->setName('test-script.php')
    ->setVersion('1.0')
    ->setDescription('Test script for Cli\Helpers\Script')
    ->addParameter(new Parameter('H', 'host'    , '127.0.0.1')              , 'Host.')
    ->addParameter(new Parameter('u', 'username', Parameter::VALUE_REQUIRED), 'User name.')
    ->addParameter(new Parameter('p', 'password', Parameter::VALUE_REQUIRED), 'Password.')
    ->addParameter(new Parameter('v', 'verbose' , Parameter::VALUE_NO_VALUE), 'Enable verbosity.')
    ->setProgram(function ($options) {
        var_dump($options);
    })
    ->start();
