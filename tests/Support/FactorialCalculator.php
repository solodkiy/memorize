<?php


namespace Solodkiy\Memorize\Support;

class FactorialCalculator
{
    private $workLog = [];

    /**
     * @var bool
     */
    private $doubleMode;

    /**
     * FactorialCalculator constructor.
     * @param bool $doubleMode https://en.wikipedia.org/wiki/Double_factorial
     */
    public function __construct($doubleMode = false)
    {
        $this->doubleMode = $doubleMode;
    }

    public function factorial($number)
    {
        return memorize(function () use ($number) {
            $this->workLog[] = 'factorial('.$number.')';

            if ($number <= 0) {
                throw new \InvalidArgumentException('Number must be natural');
            }
            if ($this->doubleMode) {
                if ($number % 2 == 0) {
                    throw new \InvalidArgumentException('Number must be odd');
                }
                return ($number == 1)
                    ? 1
                    : $number * $this->factorial($number - 2);
            } else {
                return ($number == 1)
                    ? 1
                    : $number * $this->factorial($number - 1);
            }
        });
    }

    /**
     * @return array
     */
    public function readWorkLog()
    {
        $log = $this->workLog;
        $this->workLog = [];
        return $log;
    }
}
