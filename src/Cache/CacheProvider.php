<?php

namespace Tdsereno\HttpdAnalyzer\Cache;

/**
 * @Todo: improve it and use memcached
 */
abstract class CacheProvider
{

    static $cache = [];
    static $useCache = FALSE;

    public static function setUseCache($value = TRUE)
    {
        self::$useCache = $value;
    }

    public static abstract function getFileName();

    /**
     * 
     * @param type $key
     * @param \Tdsereno\HttpdAnalyzer\Model\Base $data
     */
    public static function add($key, $data)
    {
        if(!isset(self::$cache[static::getFileName()]))
        {
            self::$cache[static::getFileName()] = new \stdClass();
        }
        self::$cache[static::getFileName()]->$key = $data;
    }

    public static function getCacheFile()
    {
        return @file_get_contents(static::getFileName());
    }

    public static function saveCacheFile($content)
    {
        return @file_put_contents(static::getFileName(), $content);
    }

    public static function loadCache()
    {
        $file = json_decode(static::getCacheFile());
        if ($file)
        {
            self::$cache[static::getFileName()] = $file;
        }
        else
        {
            self::$cache[static::getFileName()] = new \stdClass();
        }
    }

    public static function saveCache()
    {
        self::saveCacheFile(json_encode(self::$cache[static::getFileName()]));
    }

    public static function getCache($key)
    {
        if (isset(self::$cache[static::getFileName()]->$key))
        {
            return self::$cache[static::getFileName()]->$key;
        }
    }

}
