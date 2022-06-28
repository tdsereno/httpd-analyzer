<?php

namespace Tdsereno\HttpdAnalyzer\Tests\Service;

use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Service\Url
     * @return void
     */
    public function testGet(): void
    {
        // cached or nocached:
        $originalUrl = "/sistema/another/images/49190.jpg";
        $query = '?_=1642635931';
        $url = \Tdsereno\HttpdAnalyzer\Service\Url::get(new \Tdsereno\HttpdAnalyzer\Model\Url(['url' => $originalUrl]));

        $this->assertEquals($url->getUrl(), $originalUrl, 'Url must be ' . $originalUrl);
    }

}
