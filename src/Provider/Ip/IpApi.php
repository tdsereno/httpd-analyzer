<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Ip;

/**
 * https://ip-api.com/
 */
class IpApi extends Base
{

    /**
     * stdClass Object
      (
      [status] => success
      [country] => Brazil
      [countryCode] => BR
      [region] => AM
      [regionName] => Amazonas
      [city] => Manaus
      [zip] => 69000
      [lat] => -3.1032
      [lon] => -60.0288
      [timezone] => America/Manaus
      [isp] => Claro S.A.
      [org] => Claro S.A
      [as] => AS28573 Claro NXT Telecomunicacoes Ltda
      [query] => 191.189.24.203
      )
     * @param type $json
     * @return type
     */
    public static function parse($json)
    {
        $result = parent::parse($json);
        $result->setIp($json->query);
        return $result;
    }

    public static function get($ip)
    {
        if (!$ip->getIp())
        {
            return FALSE;
        }

        $details = @file_get_contents("http://ip-api.com/json/{$ip->getIp()}");
        if ($details)
        {
            return self::parse(json_decode($details));
        }
    }

}
