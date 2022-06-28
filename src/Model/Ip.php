<?php

namespace Tdsereno\HttpdAnalyzer\Model;

class Ip extends Base
{

    public $userAgent, $ip, $city, $region, $country, $org;

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getOrg()
    {
        return $this->org;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function setOrg($org)
    {
        $this->org = $org;
        return $this;
    }

    public function loadInfo()
    {
        $result = \Tdsereno\HttpdAnalyzer\Service\Ip::get($this);
        $this->setCity($result->getCity())
                ->setCountry($result->getCountry())
                ->setOrg($result->getOrg())
                ->setRegion($result->getRegion());
//                ->setCachedOn($result->getCachedOn())
        //              ->setConsultedOn($result->getConsultedOn());
        return $this;
    }

}
