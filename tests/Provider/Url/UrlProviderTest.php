<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Provider\UserAgent;

use PHPUnit\Framework\TestCase;

final class UrlProviderTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Url\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Url\ParseUrl
     * @return void
     */
    public function testGet(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\Url\ParseUrl::class];
        $originalUrl = "/sistema/another/images/49190.jpg";
        $query = '?_=1642635931';
        foreach ($providers as $provider)
        {
            $url = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Url(['url' => $originalUrl . $query]));
            $this->assertEquals($url->getUrl(), $originalUrl, 'Url must be ' . $originalUrl);

            // Implementar Guzle
            /*
              $ip = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => 'x.x.x.x']));
              $this->assertEquals(FALSE, $ip, 'Invalid Ip must be false');

             */
        }
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Url\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Url\ParseUrl
     * @return void
     */
    public function testGetType(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\Url\Base::class];
        foreach ($providers as $provider)
        {
            $this->assertEquals('url', $provider::getType());
        }
    }

}
