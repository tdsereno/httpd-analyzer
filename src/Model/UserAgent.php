<?php

namespace Tdsereno\HttpdAnalyzer\Model;

class UserAgent extends Base
{

    public $userAgent, $browser, $os, $type;

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function getOs()
    {
        return $this->os;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    public function setOs($os)
    {
        $this->os = $os;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function loadInfo()
    {
        $result = \Tdsereno\HttpdAnalyzer\Service\UserAgent::get($this);

        $this->setBrowser($result->getBrowser())
                ->setUserAgent($result->getUserAgent())
                ->setOs($result->getOs())
                ->setType($result->getType());
        return $this;
    }

}
