<?php

namespace Tdsereno\HttpdAnalyzer\Provider\UserAgent;

/**
 * Slow
 */
class DeviceDetector extends Base
{

    public static function get($userAgent)
    {
        try
        {
            $parser = new \DeviceDetector\DeviceDetector($userAgent->getUserAgent());
            $parser->parse();
        }
        catch (\Exception $ex)
        {
            return FALSE;
        }

        $cliente = $parser->getClient();

        $result->userAgent = $userAgent->getUserAgent();
        $result->browser = $cliente['family'] . ' ' . $cliente['version'];
        $result->os = $os['name'] . ' ' . $os['version'];
        $result->type = $parser->isBot() ? 'bot' : 'device';
        //$result->userAgent = $result->userAgent;

        return self::parse($result);
    }

}
