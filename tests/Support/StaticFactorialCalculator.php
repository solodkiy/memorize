<?php


namespace Solodkiy\Memorize\Support;

class StaticFactorialCalculator
{
    private static $workLog = [];

    public static function factorial($number)
    {
        return memorize(function () use ($number) {
            self::$workLog[] = 'factorial('.$number.')';

            if ($number <= 0) {
                throw new \InvalidArgumentException('Number must be natural');
            }
            return ($number == 1)
                ? 1
                : $number * self::factorial($number - 1);
        });
    }

    /**
     * @return array
     */
    public static function readWorkLog()
    {
        $log = self::$workLog;
        self::$workLog = [];
        return $log;
    }
}
