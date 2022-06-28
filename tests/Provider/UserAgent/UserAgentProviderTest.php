<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Provider\UserAgent;

use PHPUnit\Framework\TestCase;

final class UserAgentProviderTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\UserAgent\WhichBrowser
     * @return void
     */
    public function testGet(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\UserAgent\WhichBrowser::class];
        $ua = "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36";
        foreach ($providers as $provider)
        {
            $userAgent = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\UserAgent(['userAgent' => $ua]));
            $this->assertEquals($userAgent->getUserAgent(), $ua, 'User Agent must be ' . $ua);
            $this->assertEquals($userAgent->getBrowser(), 'Chrome 102', 'Browser must be Chrome 102');
            $this->assertEquals($userAgent->getOs(), 'Android 11', 'Android must be Android 11');

            // Implementar Guzle
            /*
              $ip = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => 'x.x.x.x']));
              $this->assertEquals(FALSE, $ip, 'Invalid Ip must be false');

             */
        }
    }


    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\UserAgent\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\UserAgent\WhichBrowser
     * @return void
     */
    public function testGetType(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\UserAgent\WhichBrowser::class];
        foreach ($providers as $provider)
        {
            $this->assertEquals('user-agent', $provider::getType());
        }
    }

}
