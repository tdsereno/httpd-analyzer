<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Ip;

abstract class Base extends \Tdsereno\HttpdAnalyzer\Provider\Base
{

    protected static function getCurrentProvider()
    {
        $result = parent::getCurrentProvider();
        return $result;
    }

    /**
     * {
      "ip": "8.8.8.8",
      "country": "United States",
      "country_code": "US",
      "city": null,
      "continent": "North America",
      "latitude": 37.751,
      "longitude": -97.822,
      "time_zone": "America/Chicago",
      "postal_code": null,
      "org": "GOOGLE",
      "asn": "AS15169",
      "subdivision": null,
      "subdivision2": null
      }
     * @param \stdClass $json
     * @return \Tdsereno\HttpdAnalyzer\Model\Ip
     */
    public static function parse($json)
    {
        return (new \Tdsereno\HttpdAnalyzer\Model\Ip())
                        ->setIp($json->ip)
                        ->setCity($json->city)
                        ->setRegion($json->region)
                        ->setCountry($json->country)
                        ->setOrg($json->org);
                        //->setCachedOn($json->cachedOn);
    }

    public static function getType()
    {
        return 'ip';
    }

}
