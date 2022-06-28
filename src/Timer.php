<?php

namespace Tdsereno\HttpdAnalyzer;

class Timer
{

    private static $elapsedTime = [];

    public static function addElapsedTime($name, $time)
    {
        if (!isset(self::$elapsedTime[$name]))
        {
            self::$elapsedTime[$name] = 0;
        }
        self::$elapsedTime[$name] += (microtime(true) - $time);
    }

    public static function getElapsedTimers()
    {
        uasort(self::$elapsedTime, function ($a, $b) {
            return $b - $a;
        });
        return self::$elapsedTime;
    }

    public static function get($name)
    {
        return self::$elapsedTime[$name];
    }

    public static function getTotalTime()
    {
        return array_sum(self::$elapsedTime);
    }

}
