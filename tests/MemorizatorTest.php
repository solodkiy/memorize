<?php

namespace Solodkiy\Memorize;

use PHPUnit_Framework_TestCase;

class MemorizatorTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleMemory()
    {
        $counter = 0;
        $lambda = function () use (&$counter) {
            $counter++;
            return 'value';
        };


        $storage = new Storage();
        $this->assertEquals(0, $counter);

        // First try
        $value = Memorizator::memorize('context1', $lambda, '', $storage);
        $this->assertEquals(1, $counter);
        $this->assertEquals('value', $value);

        // Second
        $value = Memorizator::memorize('context1', $lambda, '', $storage);
        $this->assertEquals(1, $counter);
        $this->assertEquals('value', $value);

        // Another context
        $value = Memorizator::memorize('context2', $lambda, '', $storage);
        $this->assertEquals(2, $counter);
        $this->assertEquals('value', $value);

        // Another params hash
        $value = Memorizator::memorize('context1', $lambda, 'new', $storage);
        $this->assertEquals(3, $counter);
        $this->assertEquals('value', $value);

        // Another storage
        $storage2 = new Storage();
        $value = Memorizator::memorize('context', $lambda, '', $storage2);
        $this->assertEquals(4, $counter);
        $this->assertEquals('value', $value);
    }

    public function testMemorizeException()
    {
        $originalException = new \DomainException('Test exception');
        $counter = 0;
        $lambda = function () use (&$counter, $originalException) {
            $counter++;
            throw $originalException;
        };

        $storage = new Storage();

        // First try
        $exception = $this->getException(function () use ($lambda, $storage) {
            Memorizator::memorize('context', $lambda, '', $storage);
        });
        $this->assertEquals(1, $counter);
        $this->assertEquals($originalException, $exception);

        // Second
        $exception = $this->getException(function () use ($lambda, $storage) {
            Memorizator::memorize('context', $lambda, '', $storage);
        });
        $this->assertEquals(1, $counter);
        $this->assertEquals($originalException, $exception);
    }

    public function testNotMemorizeException()
    {
        $originalException = new \DomainException('Test exception');
        $counter = 0;
        $lambda = function () use (&$counter, $originalException) {
            $counter++;
            throw $originalException;
        };

        $storage = new Storage();

        // First try
        $exception = $this->getException(function () use ($lambda, $storage) {
            Memorizator::memorize('context', $lambda, '', $storage, false);
        });
        $this->assertEquals(1, $counter);
        $this->assertEquals($originalException, $exception);

        // Second
        $exception = $this->getException(function () use ($lambda, $storage) {
            Memorizator::memorize('context', $lambda, '', $storage, false);
        });
        $this->assertEquals(2, $counter);
        $this->assertEquals($originalException, $exception);
    }

    private function getException(callable $lambda)
    {
        try {
            $lambda();
        } catch (\Exception $e) {
            return $e;
        }
        return null;
    }


    /**
     * @expectedException \RuntimeException
     */
    public function testIncorrectLambda()
    {
        $incorrectLambda = function ($a, $b = null) {
            return 1;
        };
        Memorizator::memorize('context', $incorrectLambda, '', new Storage());
    }
}

