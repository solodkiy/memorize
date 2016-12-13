<?php


namespace Solodkiy\Memorize\Memorize;

use DateInterval;
use SebastianBergmann\GlobalState\RuntimeException;
use Solodkiy\Memorize\Support\FactorialCalculator;

class MemorizeInObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testMemorize()
    {
        $object = new FactorialCalculator();

        // First try
        $this->assertEquals([], $object->readWorkLog());
        $this->assertEquals(120, $object->factorial(5));
        $this->assertEquals([
            'factorial(5)',
            'factorial(4)',
            'factorial(3)',
            'factorial(2)',
            'factorial(1)',
        ], $object->readWorkLog());


        // get from cache
        $this->assertEquals(6, $object->factorial(3));
        $this->assertEquals([], $object->readWorkLog());

        // get first 5 from cache
        $this->assertEquals(720, $object->factorial(6));
        $this->assertEquals([
            'factorial(6)'
        ], $object->readWorkLog());
    }

    public function testTwoObjects()
    {
        $object1 = new FactorialCalculator(false);

        $this->assertEquals(6, $object1->factorial(3));
        $this->assertEquals([
            'factorial(3)',
            'factorial(2)',
            'factorial(1)',
        ], $object1->readWorkLog());

        $object2 = new FactorialCalculator(true);
        $this->assertEquals(945, $object2->factorial(9));
        $this->assertEquals([
            'factorial(9)',
            'factorial(7)',
            'factorial(5)',
            'factorial(3)',
            'factorial(1)',
        ], $object2->readWorkLog());
    }


    /**
     * @expectedException RuntimeException
     */
    public function testIncorrectMemorizeObjects()
    {
        $day = new \DateTime('2015-01-01');
        $this->assertEquals(3, $this->incorrectAddDaysToTime($day, 2));
    }

    private function incorrectAddDaysToTime(\DateTime $time, $days)
    {
        return memorize(function () use ($time, $days) {
            return $time->add(new \DateInterval($days.' days'));
        });
    }

    public function testCorrectMemorizeObjectsParams()
    {
        $counter1 = 0;
        $day = new \DateTime('2015-01-01');
        $this->assertEquals(new \DateTime('2015-01-03'), $this->correctAddDaysToTime($day, 2, $counter1));
        $this->assertEquals(new \DateTime('2015-01-03'), $this->correctAddDaysToTime($day, 2, $counter1));
        $this->assertEquals(1, $counter1);

        $counter2 = 0;
        $day2 = new \DateTime('2015-10-01');
        $this->assertEquals(new \DateTime('2015-10-04'), $this->correctAddDaysToTime($day2, 3, $counter2));
        $this->assertEquals(new \DateTime('2015-10-04'), $this->correctAddDaysToTime($day2, 3, $counter2));
        $this->assertEquals(1, $counter2);
    }

    private function correctAddDaysToTime(\DateTime $time, $days, &$counter)
    {
        return memorize(function () use ($time, $days, &$counter) {
            $counter++;
            $newTime = clone $time;
            return $newTime->add(DateInterval::createFromDateString($days.' days'));
        }, $time->getTimestamp().'-'.$days);
    }

    public function testMemorizeMethodWithoutParams()
    {
        $numbers = [];
        foreach (range(1, 100) as $try) {
            $numbers[] = $this->getRandNumber();
        }
        $this->assertEquals(1, count(array_unique($numbers)));
    }

    private function getRandNumber()
    {
        return memorize(function () {
            return rand(1, 1000000);
        });
    }
}
