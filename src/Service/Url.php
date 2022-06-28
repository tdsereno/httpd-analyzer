<?php

namespace Tdsereno\HttpdAnalyzer\Service;

class Url extends Base
{

    public static function get($url)
    {
        return \Tdsereno\HttpdAnalyzer\Provider\Url\Base::getProvider()->get($url);
    }

}
