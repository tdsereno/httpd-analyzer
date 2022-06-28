<?php

namespace Tdsereno\HttpdAnalyzer\Provider\UserAgent;

class WhichBrowser extends Base
{

    public static function get($userAgent)
    {
        try
        {
            $result = new \WhichBrowser\Parser($userAgent->getUserAgent());
        }
        catch (\Exception $ex)
        {
            return FALSE;
        }

        $result->userAgent = $userAgent->getUserAgent();
        $result->browser = $result->browser ? $result->browser->getName() . ' ' . $result->browser->getVersion() : '';
        $result->os = $result->os ? $result->os->getName() . ' ' . $result->os->getVersion() : '';
        $result->type = $result->device ? $result->device->type : '';
        //$result->userAgent = $result->userAgent;

        return self::parse($result);
    }

}
