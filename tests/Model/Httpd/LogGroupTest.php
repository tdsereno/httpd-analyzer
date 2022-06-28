<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Model\Httpd;

use PHPUnit\Framework\TestCase;

final class LogGroupTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddUserAgent(): void
    {
        $uaAndroid = "Mozilla/5.0 (Linux; Android 11; SM-A127M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Mobile Safari/537.36";
        $uaWin = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36";

        $ua = [];
        $ua[] = $uaAndroid;
        $ua[] = $uaAndroid;
        $ua[] = $uaWin;
        $ua[] = $uaAndroid;
        $ua[] = $uaWin;
        $ua[] = $uaAndroid;

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($ua as $userAgent)
        {
            $logGroup->addUserAgent($userAgent);
        }

        $browsers = $logGroup->getBrowsers();

        $this->assertCount(2, $browsers, 'Browser quantity must be two');
        $this->assertEquals(2, $browsers[$uaWin]->getCount(), 'Count of $uaWin must bt two');
        $this->assertEquals(4, $browsers[$uaAndroid]->getCount(), 'Count of $uaWin must bt two');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddUrl(): void
    {
        $urlBaseOne = '/myOtherSystem/MyRoute/MyOther';
        $urlBaseTwo = '/myOtherSystem/MyRoute/MyOther';
        $ursl = [
            $urlBaseTwo . '/',
            $urlBaseOne . '/',
            $urlBaseOne . '/',
            $urlBaseOne . '.jpg',
            $urlBaseOne . '.jpg?time=1234',
            $urlBaseOne . '?time=1234'
        ];

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($ursl as $url)
        {
            $logGroup->addUrl($url);
        }

        $urls = $logGroup->getUrls();
        $this->assertCount(3, $urls, 'Browser quantity must be three');
        $this->assertEquals(1, $urls[$urlBaseOne]->getCount(), 'Count of $urlBaseOne must bt two');
        $this->assertEquals(1, $urls[$urlBaseTwo]->getCount(), 'Count of $urlBaseTwo must bt one');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddMethod(): void
    {
        $methods = [
            'GET' => 3, 'POST' => 4, 'PUT' => 8, 'DELETE' => 9, 'OPTIONS' => 3, 'HEAD' => 1
        ];

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($methods as $method => $qtd)
        {
            for ($i = 0; $i < $qtd; $i++)
            {
                $logGroup->addMethod($method);
            }
        }

        $totalMethods = $logGroup->getMethods();

        foreach ($methods as $method => $qtd)
        {
            $this->assertEquals($qtd, $totalMethods[$method]->getCount(), 'Count of ' . $method . ' must bt ' . $qtd);
        }
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddIp(): void
    {
        $ips = [
            '1.1.1.1' => 3, '2.2.2.2' => 4, '191.1.2.3' => 8, '8.8.8.8' => 9, '4.4.4.4' => 3, '2.2.2.2' => 1
        ];

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($ips as $ip => $qtd)
        {
            for ($i = 0; $i < $qtd; $i++)
            {
                $logGroup->addIp($ip);
            }
        }

        $totalIps = $logGroup->getIps();

        foreach ($ips as $ip => $qtd)
        {
            $this->assertEquals($qtd, $totalIps[$ip]->getCount(), 'Count of ' . $ip . ' must bt ' . $qtd);
        }
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddDate(): void
    {
        $minDate = '15/Jan/2020:00:41:44 -0300';
        $maxDate = '15/Dec/2022:00:41:44 -0300';
        $dates = [
            FALSE,
            $maxDate,
            '15/Jun/2022:00:41:44 -0300',
            '15/Jan/2022:01:21:34 -0300',
            $minDate,
            ''
        ];

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($dates as $date)
        {

            $logGroup->addDate($date);
        }

        $this->assertEquals($maxDate, $logGroup->getMaxDate(), 'Max date must be ' . $maxDate);
        $this->assertEquals($minDate, $logGroup->getMinDate(), 'Min date must be ' . $minDate);
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup
     * @return void
     */
    public function testAddStatus(): void
    {

        $status = [
            200 => 3,
            201 => 7,
            202 => 5,
            304 => 9,
            400 => 3,
            401 => 4,
            404 => 9,
            500 => 1,
            501 => 7,
        ];

        $logGroup = new \Tdsereno\HttpdAnalyzer\Model\Httpd\LogGroup();
        foreach ($status as $code => $qtd)
        {
            for ($i = 0; $i < $qtd; $i++)
            {
                $logGroup->addStatus($code);
            }
        }
        $statusGroup = $logGroup->getStatus();
        foreach ($status as $code => $qtd)
        {
            $this->assertEquals($qtd, $statusGroup[$code]->getCount(), 'Count of status  ' . $code . '. must be ' . $qtd);
        }
    }

}
