<?php

global $workLog;
global $incorrectFactorial;

$workLog = [];

function factorial($number)
{
    return memorize(function () use ($number) {
        global $workLog;
        $workLog[] = 'factorial('.$number.')';

        if ($number <= 0) {
            throw new \InvalidArgumentException('Number must be natural');
        }
        return ($number == 1)
            ? 1
            : $number * factorial($number - 1);
    });
}




