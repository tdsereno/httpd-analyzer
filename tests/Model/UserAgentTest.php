<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Model;

use PHPUnit\Framework\TestCase;

final class UserAgentTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @covers \Tdsereno\HttpdAnalyzer\Model\UserAgent
     * @return void
     */
    public function testGetterSetters(): void
    {

        $userAgent = new \Tdsereno\HttpdAnalyzer\Model\UserAgent();
        $userAgent->setUserAgent("Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36")
                ->setType('bot')
                ->setOs('Windows 10')
                ->setCount(1)
                ->setBrowser('Chrome 102');

        $this->assertEquals("Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36", $userAgent->getUserAgent(), 'teste');
        $this->assertEquals('bot', $userAgent->getType(), 'teste');
        $this->assertEquals('Windows 10', $userAgent->getOs(), 'teste');
        $this->assertEquals('1', $userAgent->getCount(), 'teste');
        $this->assertEquals('Chrome 102', $userAgent->getBrowser(), 'teste');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @covers \Tdsereno\HttpdAnalyzer\Model\UserAgent
     * @return void
     */
    public function testConstructor(): void
    {
        $array = [
            'userAgent' => "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36"
        ];

        $ip = new \Tdsereno\HttpdAnalyzer\Model\UserAgent($array);
        $this->assertEquals($array['userAgent'], $ip->getUserAgent(), 'teste');

        $ip = new \Tdsereno\HttpdAnalyzer\Model\Ip((object) $array);
        $this->assertEquals($array['userAgent'], $ip->getUserAgent(), 'teste');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Base
     * @covers \Tdsereno\HttpdAnalyzer\Model\UserAgent
     * @return void
     */
    public function testJsonSerializer(): void
    {
        $array = [
            'userAgent' => "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36"
        ];
        $userAgent = new \Tdsereno\HttpdAnalyzer\Model\UserAgent($array);
        $json = $userAgent->jsonSerialize();
        $this->assertEquals($json->userAgent, $userAgent->getUserAgent(), 'Json Serialize User Agent must be equals to userAgent');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\UserAgent
     * @return void
     */
    public function testLoadInfo(): void
    {

        \Tdsereno\HttpdAnalyzer\Helper::loadEnv();
        $array = [
            'userAgent' => "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36"
        ];
        $userAgent = new \Tdsereno\HttpdAnalyzer\Model\UserAgent($array);
        $userAgent->loadInfo();

        $this->assertEquals($userAgent->getUserAgent(), $array['userAgent'], 'User Agent must be ' . $array['userAgent']);
        $this->assertEquals($userAgent->getBrowser(), 'Chrome 102', 'Browser must be Chrome 102');
        $this->assertEquals($userAgent->getOs(), 'Android 11', 'Android must be Android 11');
    }

}
