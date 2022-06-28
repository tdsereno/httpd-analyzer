<?php

namespace Tdsereno\HttpdAnalyzer\Tests;

use PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Helper
     * @uses \Tdsereno\HttpdAnalyzer\Helper 
     * @return void
     */
    public function testParseBytes(): void
    {
        $this->assertEquals('1MB', \Tdsereno\HttpdAnalyzer\Helper::parseBytes(1000000), 'parseBytes de 1024 Bytes deve ser 1MB');
    }

}
