<?php

namespace Tdsereno\HttpdAnalyzer\Provider\UserAgent;

abstract class Base extends \Tdsereno\HttpdAnalyzer\Provider\Base
{

    public static function parse($result)
    {
        return (new \Tdsereno\HttpdAnalyzer\Model\UserAgent())
                        ->setUserAgent($result->userAgent)
                        ->setBrowser($result->browser)
                        ->setOs($result->os)
                        ->setType($result->type);
    }

    public static function getType()
    {
        return 'user-agent';
    }

}
