<?php


namespace Solodkiy\Memorize\Memorize;

use Solodkiy\Memorize\Support\Singleton;
use Solodkiy\Memorize\Support\StaticFactorialCalculator;

class MemorizeInStaticTest extends \PHPUnit_Framework_TestCase
{

    public function testMemorize()
    {
        // First try
        $this->assertEquals([], StaticFactorialCalculator::readWorkLog());
        $this->assertEquals(120, StaticFactorialCalculator::factorial(5));
        $this->assertEquals([
            'factorial(5)',
            'factorial(4)',
            'factorial(3)',
            'factorial(2)',
            'factorial(1)',
        ], StaticFactorialCalculator::readWorkLog());


        // get from cache
        $this->assertEquals(6, StaticFactorialCalculator::factorial(3));
        $this->assertEquals([], StaticFactorialCalculator::readWorkLog());

        // get first 5 from cache
        $this->assertEquals(720, StaticFactorialCalculator::factorial(6));
        $this->assertEquals([
            'factorial(6)'
        ], StaticFactorialCalculator::readWorkLog());
    }

    public function testSingleton()
    {
        $first = Singleton::getInstance();
        $second = Singleton::getInstance();
        $this->assertSame($first, $second);
        $this->assertInstanceOf('Solodkiy\Memorize\Support\Singleton', $first);
    }


}