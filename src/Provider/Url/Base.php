<?php

namespace Tdsereno\HttpdAnalyzer\Provider\Url;

abstract class Base extends \Tdsereno\HttpdAnalyzer\Provider\Base
{

    protected static function getCurrentProvider()
    {
        $result = parent::getCurrentProvider();
        if (!$result)
        {
            return ParseUrl::class;
        }
        return $result;
    }

    public static function getType()
    {
        return 'url';
    }

}
