<?php

namespace Tdsereno\HttpdAnalyzer\Service;

class Ip extends Base
{

    public static function get($ip)
    {
        \Tdsereno\HttpdAnalyzer\Cache\IpCache::loadCache();
        if (\Tdsereno\HttpdAnalyzer\Helper::useCache())
        {
            $cached = \Tdsereno\HttpdAnalyzer\Cache\IpCache::get($ip->getIp());
            if ($cached)
            {
                return $cached;
            }
        }

        $noCached = \Tdsereno\HttpdAnalyzer\Provider\Ip\Base::getProvider()->get($ip);
        if ($noCached)
        {
            return $noCached;
        }

        return $ip;
    }

}
