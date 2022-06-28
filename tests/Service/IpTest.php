<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Service;

use PHPUnit\Framework\TestCase;

final class IpTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Service\Ip
     * @return void
     */
    public function testGet(): void
    {
        \Tdsereno\HttpdAnalyzer\Helper::loadEnv();
        // cached or nocached:
        $ip = \Tdsereno\HttpdAnalyzer\Service\Ip::get(new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => '8.8.8.8']));
        $this->assertEquals('8.8.8.8', $ip->getIp(), 'Ip must be 8.8.8.8');

        $this->assertContains($ip->getCountry(), ['United States', 'US'], 'Country must be United States or US', TRUE);
    }

}
