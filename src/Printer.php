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

}
