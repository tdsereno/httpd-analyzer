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
      
        try
        {
            $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
            $dotenv->load();
        }
        catch (\Exception $ex)
        {
            
        }

        Provider\Ip\Base::setCurrentProvider(getenv('IP_PROVIDER') ?: \Tdsereno\HttpdAnalyzer\Provider\Ip\IpInfo::class);
        Provider\UserAgent\Base::setCurrentProvider(getenv('USERAGENT_PROVIDER') ?: \Tdsereno\HttpdAnalyzer\Provider\UserAgent\WhichBrowser::class);
        if (getenv('USE_CACHE') === 'TRUE')
        {
            Cache\CacheProvider::setUseCache();
        }
    }

}
