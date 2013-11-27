<?php

namespace Cli\Helpers;

/**
 * Cli\Helpers\IO
 * ==============
 *
 * Utility class to handle standard input/output.
 *
 * Usage
 * -----
 *
 * ```php
 * \Cli\Helpers\IO::form('an apple', array(
 *     'Golden Delicious',
 *     'Granny Smith',
 *     'Pink Lady',
 *     'Royal Gala',
 * ));
 * ```
 * will display:
 * ```
 * 1. Golden Delicious
 * 2. Granny Smith
 * 3. Pink Lady
 * 4. Royal Gala
 *
 * Choose an apple: |
 * ```
 * Then user is asked to make a choice between 1 and 3 on standard input
 */
class IO
{
    const STDIN = 'php://stdin';

    public static function readInteger($min, $max, $inputHandler = null)
    {
        $handler = $inputHandler !== null ? $inputHandler : fopen(self::STDIN, 'r');

        $response = trim(fgets($handler));
        if (is_numeric($response) && ($val = intval($response)) >= $min && $val <= $max) {
            return $val;
        }

        $inputHandler === null && fclose($inputHandler);
        return false;
    }

    public static function form($name, $items, $inputHandler = null)
    {
        $length = 1 + round(log(count($items)) / log(10));
        foreach ($items as $i => $item) {
            echo str_pad($i + 1, $length, ' ', STR_PAD_LEFT) . '. ' . $item . "\n";
        }
        echo "\nChoose $name: ";

        $count = count($items);
        while (($response = self::readInteger(1, $count, $inputHandler)) === false) {
            echo "\nPlease enter a number between 1 and $count: ";
        }

        return $response - 1;
    }
}
