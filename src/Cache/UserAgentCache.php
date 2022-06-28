<?php

namespace Tdsereno\HttpdAnalyzer\Cache;

class UserAgentCache extends CacheProvider
{

    public static function getFileName()    
    {
        return __DIR__ . '/../../storage/cache/user_agent_cache.json';
    }

    /**
     * 
     * @param type $ip
     * @return \Tdsereno\HttpdAnalyzer\Model\UserAgent|boolean
     */
    public static function get($userAgent)
    {
        if (!$userAgent instanceof \Tdsereno\HttpdAnalyzer\Model\UserAgent)
        {
            $userAgent = (new \Tdsereno\HttpdAnalyzer\Model\UserAgent)->setUserAgent($userAgent);
        }
        $provider = \Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base::getProvider();

        $cached = self::getCache($userAgent->getUserAgent());
        if ($cached)
        {
            return $provider->parse($cached);
        }

        $noCached = $provider->get($userAgent->getUserAgent());
       // $noCached->setCachedOn(date("Y-m-d H:i:s"));
        self::add($noCached->getUserAgent(), $noCached);
        return $noCached;
    }

    public static function set($userAgent)
    {
        $cached = self::getCache($userAgent);
        if ($cached)
        {
            return $cached;
        }

        $provider = \Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base::getProvider();
        $noCached = $provider->get($userAgent);
       // $noCached->cachedOn = date("Y-m-d H:i:s");
        self::add($userAgent->getUserAgent(), $noCached);
    }

}
