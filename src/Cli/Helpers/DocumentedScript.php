<?php

namespace Cli\Helpers;

class DocumentedScript extends Script
{
    public function __construct()
    {
        $this->addVersionParameter();
        $this->addHelpParameter();
    }

    protected function addVersionParameter()
    {
        $this->addParameter(
            new Parameter('V', 'version', Parameter::VALUE_NO_VALUE),
            'Output version information and exit.',
            function ($options, $arguments, $that) {
                echo $that->name . ' v' . $that->version . "\n"
                    . $that->copyright . "\n";
                return false;
            }
        );
    }

    protected function addHelpParameter()
    {
        $this->addParameter(
            new Parameter('h', 'help', Parameter::VALUE_NO_VALUE),
            'Display this help and exit.',
            function ($options, $arguments, $that) {
                echo 'Usage: ' . $arguments[0];

                $options = array();
                foreach (array_reverse($that->parameters, true) as $id => $parameter) {

                    // Show in syntax line if required
                    if ($parameter->getDefaultValue() === Parameter::VALUE_REQUIRED) {
                        echo ' ' . $parameter->getShortSwitch()
                            . ' ' . str_replace('-', '_', strtoupper($parameter->getLong()));
                    }

                    // Add to options
                    $options[] = array(
                        '  ' . $parameter->getShortSwitch() . ', ' . $parameter->getLongSwitch(),
                        (
                            $parameter->getDefaultValue() !== Parameter::VALUE_NO_VALUE
                            ? str_replace('-', '_', strtoupper($parameter->getLong()))
                            : ''
                        ),
                        '  ',
                        (
                            $parameter->getDefaultValue() !== Parameter::VALUE_REQUIRED
                            && $parameter->getDefaultValue() !== Parameter::VALUE_NO_VALUE
                            ? preg_replace(
                                '/(\\.)?$/',
                                ' (defaults to \'' . $parameter->getDefaultValue() . '\')$1',
                                $that->parameterDescriptions[$id],
                                1
                            )
                            : $that->parameterDescriptions[$id]
                        ),
                    );
                }

                echo " [OPTIONS]\n"
                    . "\n"
                    . $this->description . "\n"
                    . "\n"
                    . IO::strPadAll($options, array(), "\n", ' ', ' ', false) . "\n"
                    . "\n"
                    . $this->name . ' v' . $this->version . "\n"
                    . ($this->copyright ? 'Copyright (c) ' . $this->copyright . "\n" : '');
                return false;
            }
        );

    }
}
