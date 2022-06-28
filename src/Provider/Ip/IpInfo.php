<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Ip;

/**
 * Free plan - 1k request per day - dont need token
 * Free plan - 50k requests per month - need token
 * https://ipinfo.io/account/home show usage (nice)
 */
class IpInfo extends Base
{

    public static function get($ip)
    {
        if (!$ip->getIp())
        {
            return FALSE;
        }
        $token = getenv('TOKEN_IP_INFO_IO');
        $details = @file_get_contents("http://ipinfo.io/{$ip->getIp()}/json" . ($token ? "?token=$token" : ''));
        if ($details)
        {
            return self::parse(json_decode($details));
        }
    }

}
