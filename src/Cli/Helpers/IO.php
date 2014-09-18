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
        $inputHandler === null && fclose($handler); // close only if opened here

        if (is_numeric($response) && ($val = intval($response)) >= $min && $val <= $max) {
            return $val;
        }
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

    public static function strPadAll(
        $lines,
        $modes = array(),
        $lineSeparator = "\n",
        $columnSeparator = ' ',
        $fillCharacter = ' ',
        $trim = true
    ) {
        // Find the max length of each column
        $wordLengths = array();
        foreach ($lines as $i => $words) {
            if (!is_array($words)) {
                $words = array($words);
                $lines[$i] = $words;
            }
            foreach ($words as $j => $word) {
                $length = strlen($word);
                $wordLengths[$j] =
                    array_key_exists($j, $wordLengths)
                    ? max($wordLengths[$j], $length)
                    : $length;
            }
        }

        // Create the output
        $output = '';
        foreach ($lines as $i => $words) {
            $output .= $i === 0 ? '' : $lineSeparator;

            $line = '';
            foreach ($words as $j => $word) {
                $mode = array_key_exists($j, $modes) ? $modes[$j] : STR_PAD_RIGHT;
                $line .= $j === 0 ? '' : $columnSeparator;
                $line .= str_pad($word, $wordLengths[$j], $fillCharacter, $mode);
            }
            $output .= $trim ? trim($line) : preg_replace('/\\s*$/', '', $line);
        }

        return $output;
    }
}
