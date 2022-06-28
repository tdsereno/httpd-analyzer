<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Provider\Ip;

use PHPUnit\Framework\TestCase;

final class IpProviderTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Ip\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Ip\IpApi
     * @covers  \Tdsereno\HttpdAnalyzer\Provider\Ip\IpInfo
     * @covers  \Tdsereno\HttpdAnalyzer\Provider\Ip\IpLocate
     * @return void
     */
    public function testGet(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\Ip\IpApi::class,
            \Tdsereno\HttpdAnalyzer\Provider\Ip\IpInfo::class,
            \Tdsereno\HttpdAnalyzer\Provider\Ip\IpLocate::class];
        foreach ($providers as $provider)
        {
            $ip = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => '8.8.8.8']));
            $this->assertEquals('8.8.8.8', $ip->getIp(), 'Ip must be 8.8.8.8');
            $this->assertContains($ip->getCountry(), ['United States', 'US'], 'Country must be United States or US', TRUE);

            $ip = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Ip());
            $this->assertEquals(FALSE, $ip, 'No Ip must be false');

            // Implementar Guzle
            /*
              $ip = $provider::get(new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => 'x.x.x.x']));
              $this->assertEquals(FALSE, $ip, 'Invalid Ip must be false');

             */
        }
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Ip\Base
     * @covers \Tdsereno\HttpdAnalyzer\Provider\Ip\IpApi
     * @covers  \Tdsereno\HttpdAnalyzer\Provider\Ip\IpInfo
     * @covers  \Tdsereno\HttpdAnalyzer\Provider\Ip\IpLocate
     * @return void
     */
    public function testGetType(): void
    {
        $providers = [\Tdsereno\HttpdAnalyzer\Provider\Ip\IpApi::class,
            \Tdsereno\HttpdAnalyzer\Provider\Ip\IpInfo::class,
            \Tdsereno\HttpdAnalyzer\Provider\Ip\IpLocate::class];
        foreach ($providers as $provider)
        {
            $this->assertEquals('ip', $provider::getType());
        }
    }

}
