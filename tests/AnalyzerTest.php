<?php

namespace Tdsereno\HttpdAnalyzer\Tests;

use PHPUnit\Framework\TestCase;

final class AnalyzerTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Analyzer
     * @return void
     */
    public function testAddLogData(): void
    {
        $lines = [
            'www.mydomain.com.br 172.70.110.100 - - [15/Jun/2022:00:41:44 -0300]  "GET /images/product/ball.jpg HTTP/1.1" 404 9263 "-" "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36"',
            'www.myotherdomain.com.br 172.70.110.100  - - [15/Jun/2022:00:41:42 -0300]  "GET /products?&orderby=price HTTP/1.1" 200 18672 "-" "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)"'
        ];
        $analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();
        foreach ($lines as $line)
        {
            $analyzer->addLogData($line, 'test.log', \Tdsereno\HttpdAnalyzer\Analyzer::FORMAT_WITH_SERVER);
        }
        $analyzer->load();

        $logsGroup = $analyzer->getLogGroup();

        $this->assertCount(2, $logsGroup, 'Log Group must have two domains');
    }

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Analyzer
     * @return void
     */
    public function testProcessSingleFile(): void
    {
        $analyzer = new \Tdsereno\HttpdAnalyzer\Analyzer();

        $analyzer->addFile(__DIR__ . '/Logs/access_log_nameserver.txt');
        
        $analyzer->load();

        $logsGroup = $analyzer->getLogGroup();
        $this->assertCount(4, $logsGroup, 'Log Group must have four domains');
    }

}
