<?php

namespace Tdsereno\HttpdAnalyzer\Model;

class Status extends Base
{

    protected $status;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function loadInfo()
    {
        throw new \Exception('Load Info dont implemented on Status');
    }

}
