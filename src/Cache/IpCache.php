<?php

namespace Tdsereno\HttpdAnalyzer\Cache;

class IpCache extends CacheProvider
{

    public static function getFileName()
    {
        return __DIR__ . '/../../storage/cache/ip_cache.json';
    }

    /**
     * 
     * @param type $ip
     * @return \Tdsereno\HttpdAnalyzer\Model\Ip|boolean
     */
    public static function get($ip)
    {
        $cached = self::getCache($ip);

        if ($cached)
        {
            return new \Tdsereno\HttpdAnalyzer\Model\Ip($cached);
        }

        return FALSE;
    }

    public static function set($ip)
    {
        $cached = self::getCache($ip);
        if ($cached)
        {
            return $cached;
        }

        $provider = \Tdsereno\HttpdAnalyzer\Provider\Ip\Base::getProvider();
        $noCached = $provider->get($ip);
        $noCached->cachedOn = date("Y-m-d H:i:s");
        self::add($ip->getIp(), $ip);
    }

}
