<?php

namespace Tdsereno\HttpdAnalyzer\Model;

class Method extends Base
{

    protected $method;

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function loadInfo()
    {
        throw new \Exception('Load Info dont implemented on Method');
    }

}
