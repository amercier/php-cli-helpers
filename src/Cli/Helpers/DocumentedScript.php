<?php

namespace Cli\Helpers;

class DocumentedScript extends Script
{
    public function __construct()
    {
        $this->addParameter(
            new Parameter('h', 'help', Parameter::VALUE_NO_VALUE),
            'Display this help and exit.',
            function ($arguments) {
                echo 'Usage: ' . $arguments[0] . "[OPTIONS]\n"
                    . "\n"
                    . $this->description . "\n"
                    . "\n"
                    . $this->name . ' v' . $this->version . "\n"
                    . 'Copyright (c) ' . $this->copyright . "\n";
                return false;
            }
        );
        $this->addParameter(
            new Parameter('v', 'version', Parameter::VALUE_NO_VALUE),
            'Output version information and exit.',
            function ($arguments) {
                echo $this->name . ' v' . $this->version . "\n"
                    . 'Copyright (c) ' . $this->copyright . "\n";
                return false;
            }
        );
    }
}
