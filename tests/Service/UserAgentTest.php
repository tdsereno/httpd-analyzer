<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Service;

use PHPUnit\Framework\TestCase;

final class UserAgentTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Service\UserAgent
     * @return void
     */
    public function testGet(): void
    {
        \Tdsereno\HttpdAnalyzer\Helper::loadEnv();
        \Tdsereno\HttpdAnalyzer\Cache\UserAgentCache::loadCache();
        // cached or nocached:
        $ua = "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36";
        $userAgent = \Tdsereno\HttpdAnalyzer\Service\UserAgent::get(new \Tdsereno\HttpdAnalyzer\Model\UserAgent(['userAgent' => $ua]));

        $this->assertEquals($ua, $userAgent->getUserAgent(), 'User Agent must be ' . $ua);
        $this->assertEquals($userAgent->getBrowser(), 'Chrome 102', 'Browser must be Chrome 102');
        $this->assertEquals($userAgent->getOs(), 'Android 11', 'Android must be Android 11');

        $ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36";
        $userAgent = \Tdsereno\HttpdAnalyzer\Service\UserAgent::get(new \Tdsereno\HttpdAnalyzer\Model\UserAgent(['userAgent' => $ua]));

        $this->assertEquals($ua, $userAgent->getUserAgent(), 'User Agent must be ' . $ua);
        $this->assertEquals($userAgent->getBrowser(), 'Chrome 102', 'Browser must be Chrome 102');
        $this->assertEquals($userAgent->getOs(), 'Windows 10', 'Android must be Android 11');
    }

}
