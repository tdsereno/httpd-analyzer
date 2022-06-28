<?php

namespace Tdsereno\HttpdAnalyzer\Tests;

use PHPUnit\Framework\TestCase;

final class TimerTest extends TestCase
{

    /**
     * @covers \Tdsereno\HttpdAnalyzer\Timer
     * @uses \Tdsereno\HttpdAnalyzer\Helper 
     * @return void
     */
    public function testaddElapsedTime(): void
    {
        $timer = microtime(TRUE);
        sleep(1);
        \Tdsereno\HttpdAnalyzer\Timer::addElapsedTime('teste', $timer);
        $elapsed = \Tdsereno\HttpdAnalyzer\Timer::get('teste');
        $this->assertGreaterThan(1, $elapsed, 'Tempo passado deve ser 1');
        $this->assertLessThan(1.1, $elapsed, 'Tempo passado deve ser 1');

        $this->assertIsArray(\Tdsereno\HttpdAnalyzer\Timer::getElapsedTimers());

        $this->assertGreaterThan(1, \Tdsereno\HttpdAnalyzer\Timer::getTotalTime(), 'Tempo passado deve ser 1');
        $this->assertLessThan(1.1, \Tdsereno\HttpdAnalyzer\Timer::getTotalTime(), 'Tempo passado deve ser 1');
    }

}
