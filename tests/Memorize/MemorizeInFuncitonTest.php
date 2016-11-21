<?php


namespace Solodkiy\Memorize\Memorize;

class MemorizeInFunctionTest extends \PHPUnit_Framework_TestCase
{
    public function testMemorize()
    {
        global $workLog;
        $workLog = [];
        // First try
        $this->assertEquals([], $workLog);
        $this->assertEquals(120, factorial(5));
        $this->assertEquals([
            'factorial(5)',
            'factorial(4)',
            'factorial(3)',
            'factorial(2)',
            'factorial(1)',
        ], $workLog);

        $workLog = [];

        // get from cache
        $this->assertEquals(6, factorial(3));
        $this->assertEquals([], $workLog);

        // get first 5 from cache
        $this->assertEquals(720, factorial(6));
        $this->assertEquals([
            'factorial(6)'
        ], $workLog);
    }


}