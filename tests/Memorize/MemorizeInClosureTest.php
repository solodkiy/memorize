<?php


namespace Solodkiy\Memorize\Memorize;

class MemorizeInClosureTest extends \PHPUnit_Framework_TestCase
{
    private $workLog = [];

    protected function setUp()
    {
        $this->workLog = [];
    }

    public function testMemorizeWithGuessParameters()
    {
        $function1 = function ($number)  {
            return memorize(function () use ($number) {
                $this->workLog[] = 'a '. $number;
                return $number;
            });
        };

        $function2 = function ($number)  {
            return memorize(function () use ($number) {
                $this->workLog[] = 'b '.$number;
                return $number;
            });
        };

        $this->assertEquals(5, $function1(5));
        $this->assertEquals(5, $function1(5));
        $this->assertEquals(['a 5'], $this->workLog);
        $this->workLog = [];

        $this->assertEquals(5, $function2(5));
        $this->assertEquals(4, $function2(4));
        $this->assertEquals(5, $function2(5));
        $this->assertEquals(4, $function2(4));
        $this->assertEquals(['b 5', 'b 4'], $this->workLog);
    }

    public function testCircle()
    {
        $numbers = [];
        foreach (range(1, 100) as $i) {
            $number = $i % 10;
            $numbers[] = memorize(function () use ($number) {
                $this->workLog[] = 'c '. $number;
                return $number;
            });
        };

        $this->assertEquals(100, count($numbers));
        $this->assertEquals(10, count($this->workLog));
        $this->assertEquals(10, count(array_unique($numbers)));

    }

}