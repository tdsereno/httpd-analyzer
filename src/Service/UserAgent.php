<?php

namespace Tdsereno\HttpdAnalyzer\Service;

class UserAgent extends Base
{

    /**
     * 
     * @param type $userAgent
     * @return \Tdsereno\HttpdAnalyzer\Model\UserAgent
     */
    public static function get($userAgent)
    {
        \Tdsereno\HttpdAnalyzer\Cache\UserAgentCache::loadCache();
        if (\Tdsereno\HttpdAnalyzer\Helper::useCache())
        {
            $cached = \Tdsereno\HttpdAnalyzer\Cache\UserAgentCache::get($userAgent->getUserAgent());
            if ($cached)
            {
                return $cached;
            }
        }

        $noCached = \Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base::getProvider()->get($userAgent);
        if ($noCached)
        {
            return $noCached;
        }

        return $userAgent;
    }

}
