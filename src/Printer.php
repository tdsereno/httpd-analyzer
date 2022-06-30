<?php

namespace Tdsereno\HttpdAnalyzer;

class Printer
{

    static $avoidColor = FALSE;

    public static function getAvoidColor()
    {
        return self::$avoidColor;
    }

    public static function setAvoidColor($avoidColor)
    {
        self::$avoidColor = $avoidColor;
        return;
    }

    public static function debugTextGreen($text, $size = 1, $pad = STR_PAD_LEFT)
    {
        if (self::getAvoidColor())
        {
            self::debug($text, $size, $pad);
            return;
        }
        echo "\033[32m";
        self::debug($text, $size, $pad);
        echo "\033[0m";
    }

    public static function debugTextRed($text, $size = 1, $pad = STR_PAD_LEFT)
    {
        if (self::getAvoidColor())
        {
            self::debug($text, $size, $pad);
            return;
        }
        echo "\033[31m";
        self::debug($text, $size, $pad);
        echo "\033[0m";
    }

    public static function debugTextYellow($text, $size = 1, $pad = STR_PAD_LEFT)
    {
        if (self::getAvoidColor())
        {
            self::debug($text, $size, $pad);
            return;
        }
        echo "\033[33m";
        self::debug($text, $size, $pad);
        echo "\033[0m";
    }

    public static function debugTextBgGreen($text, $size = 1, $pad = STR_PAD_LEFT)
    {
        if (self::getAvoidColor())
        {
            self::debug($text, $size, $pad);
            return;
        }
        echo "\e[1;37;42m";
        self::debug($text, $size, $pad);
        echo "\e[0m";
    }

    public static function debug($text, $size = NULL, $pad = STR_PAD_LEFT)
    {
        if (!$size)
        {
            $sise = mb_strlen($text);
        }
        echo( self::mb_str_pad(mb_substr($text, 0, $size), $size, ' ', $pad) . '|');
    }

    public static function newLine()
    {
        echo(PHP_EOL . '|');
    }

    private static function mb_str_pad($input, $padLength, $padString, $padStyle, $encoding = "UTF-8")
    {
        return str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $padLength, $padString, $padStyle);
    }

    public static function clearTerminal()
    {
        echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
        //system('clear');
    }

    public static function memoryUsage()
    {
        $menUsage = memory_get_usage(true);

        if ($menUsage < 1024)
            self::debug($menUsage . " bytes");
        elseif ($menUsage < 1048576)
            self::debug(round($menUsage / 1024, 2) . " kilobytes");
        else
            self::debug(round($menUsage / 1048576, 2) . " megabytes");

        self::newLine();
    }

    public static function replaceOut($str)
    {

        //https://stackoverflow.com/a/27850902/14626048
        // Return to the beginning of the line
        echo "\r";
        // Erase to the end of the line
        echo "\033[K";
        // Move cursor Up a line
        echo "\033[1A";
        // Return to the beginning of the line
        echo "\r";
        // Erase to the end of the line
        echo "\033[K";
        // Return to the beginning of the line
        echo "\r";
        echo($str);
        // Can be consolodated into
        // echo "\r\033[K\033[1A\r\033[K\r";
        /*
          //https://stackoverflow.com/a/5265440/14626048
          echo "\033[" . strlen($str) . "D";      // Move 5 characters backward
          echo $str;    // Output is always 5 characters long
          return;
          sleep(1);           // wait for a while, so we see the animation
         */

        $numNewLines = substr_count($str, "\n");
        echo chr(27) . "[0G"; // Set cursor to first column
        echo $str;
        echo chr(27) . "[" . $numNewLines . "A"; // Set cursor up x lines
    }

}
