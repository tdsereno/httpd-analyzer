<?php

namespace Tdsereno\HttpdAnalyzer;

class Helper
{

    public static function useCache()
    {
        return FALSE;
    }

    public static function parseBytes($bytes)
    {
        return round($bytes / 1024 / 1024, 1) . 'MB';
    }

    public static function loadEnv()
    {
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
        $dotenv->load();

        Provider\Ip\Base::setCurrentProvider(getenv('IP_PROVIDER'));
        Provider\UserAgent\Base::setCurrentProvider(getenv('USERAGENT_PROVIDER'));
        if(getenv('USE_CACHE') === 'TRUE')
        {
            Cache\CacheProvider::setUseCache();
        }
        
        
    }

}
