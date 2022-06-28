<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Model;

use PHPUnit\Framework\TestCase;

final class IpTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @covers \Tdsereno\HttpdAnalyzer\Model\Ip
     * @return void
     */
    public function testConstructor(): void
    {
        $array = [
            'ip' => '8.8.8.8'
        ];

        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip($array);
        $this->assertEquals($array['ip'], $ip->getIp(), 'teste');

        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip((object) $array);
        $this->assertEquals($array['ip'], $ip->getIp(), 'teste');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @covers \Tdsereno\HttpdAnalyzer\Model\Ip
     * @return void
     */
    public function testJsonSerializer(): void
    {
        $array = [
            'ip' => '8.8.8.8'
        ];
        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip($array);
        $json = $ip->jsonSerialize();
        $this->assertEquals($json->ip, $ip->getIp(), 'Json Seriali IP must be equals to object ip');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Ip
     * @return void
     */
    public function testLoadInfo(): void
    {
        \Tdsereno\HttpdAnalyzer\Helper::loadEnv();
        // cached or nocached:
        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => '8.8.8.8']);
        $ip->loadInfo();
        $this->assertEquals('8.8.8.8', $ip->getIp(), 'Ip must be 8.8.8.8');
        // $this->assertEquals('GOOGLE', $ip->getOrg(), 'Org must be Google');
        //$this->assertEquals('United States', $ip->getCountry(), );
        $this->assertContains($ip->getCountry(), ['United States', 'US'], 'Country must be United States or US', TRUE);
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @return void
     */
    public function testClone(): void
    {
        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip(['ip' => '8.8.8.8']);
        $ipClone = $ip->clone();
        $this->assertEquals($ip->getIp(), $ipClone->getIp(), 'Ip must be ' . $ip->getIp());
        $ip->setIp('1.1.1.1');
        $this->assertNotEquals($ip->getIp(), $ipClone->getIp(), 'Ip must be diffrent');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Ip
     * @covers \Tdsereno\HttpdAnalyzer\Model\Method
     * @covers \Tdsereno\HttpdAnalyzer\Model\Status
     * @covers \Tdsereno\HttpdAnalyzer\Model\Url
     * @covers \Tdsereno\HttpdAnalyzer\Model\UserAgent
     * @return void
     */
    public function testBaseGetSet(): void
    {

        $models = [\Tdsereno\HttpdAnalyzer\Model\Ip::class,
            \Tdsereno\HttpdAnalyzer\Model\Method::class,
            \Tdsereno\HttpdAnalyzer\Model\Status::class,
            \Tdsereno\HttpdAnalyzer\Model\Url::class,
            \Tdsereno\HttpdAnalyzer\Model\UserAgent::class,
        ];
        foreach ($models as $model)
        {
            /**
             * @var $base \Tdsereno\HttpdAnalyzer\Model\Base
             */
            $base = new $model();
            foreach ($base->getAttributes() as $attribute => $value)
            {
                $methodGet = 'get' . $attribute;
                if (!method_exists($base, $methodGet))
                {
                    $this->fail('Method ' . $methodGet . ' does not exists');
                }
                $methodSet = 'set' . $attribute;
                if (!method_exists($base, $methodSet))
                {
                    $this->fail('Method ' . $methodSet . ' does not exists');
                }
                $bytes = random_bytes(16);
                $base->$methodSet($bytes);

                $this->assertEquals($bytes, $base->$methodGet(), $methodGet . ' must return exactly what was passed to set');
            }
        }
    }

}
