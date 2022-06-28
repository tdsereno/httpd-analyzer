<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Ip;

/**
 * The API is free to use up to 1,000 requests per day. You don’t need an API key.
 */
class IpLocate extends Base
{

    public static function get($ip)
    {
        if (!$ip->getIp())
        {
            return FALSE;
        }
        //$url = "http://ipinfo.io/{$ip}/json";
        /*
          $opts = array('http' => array('method' => "GET", 'header' => "User-Agent: mybot.v0.7.1"));
          $context = stream_context_create($opts);
          $url = "https://ipapi.co/{$ip}/json/";
          $details = json_decode(file_get_contents($url, true, $context)); */
        //$details = file_get_contents("http://api.hostip.info/?ip={$ip}");
        //$details = file_get_contents("https://api.hostip.info/get_json.php?ip={$ip}");
        $details = @file_get_contents("https://www.iplocate.io/api/lookup/{$ip->getIp()}");
        if ($details)
        {
            return self::parse(json_decode($details));
        }
    }

}
