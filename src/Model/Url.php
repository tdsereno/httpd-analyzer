<?php

namespace Tdsereno\HttpdAnalyzer\Model;

class Url extends Base
{

    protected $url, $query, $isSuspicious, $suspiciousReason;

    public function getUrl()
    {
        return $this->url;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    public function getIsSuspicious()
    {
        return $this->isSuspicious;
    }

    public function getSuspiciousReason()
    {
        return $this->suspiciousReason;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setIsSuspicious($isSuspicious)
    {
        $this->isSuspicious = $isSuspicious;
        return $this;
    }

    public function setSuspiciousReason($suspiciousReason)
    {
        $this->suspiciousReason = $suspiciousReason;
        return $this;
    }

    public function loadInfo()
    {
        $result = \Tdsereno\HttpdAnalyzer\Service\Url::get($this);
        if ($result)
        {
            $this->setAttributes($result);
        }
//                ->setCachedOn($result->getCachedOn())
        //              ->setConsultedOn($result->getConsultedOn());
        return $this;
    }

}
