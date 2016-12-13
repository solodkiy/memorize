<?php


namespace Solodkiy\Memorize;

use RuntimeException;

class Memorizator
{
    public static function memorize($contextName, callable $lambda, $paramsHash, Storage $storage, $memorizeExceptions = true)
    {
        if ($storage->has($contextName, $paramsHash)) {
            $returnPair = $storage->get($contextName, $paramsHash);
        } else {
            $returnPair = self::runLambda($lambda);
            if (self::shouldMemorizeResult($returnPair, $memorizeExceptions)) {
                $storage->set($contextName, $paramsHash, $returnPair);
            }
        }

        return self::returnResult($returnPair);
    }

    private static function runLambda(callable $lambda)
    {
        $lambdaReflection = new \ReflectionFunction($lambda);
        if ($lambdaReflection->getNumberOfRequiredParameters()) {
            throw new RuntimeException('You can memorize only lambda without params');
        }

        $returnValue = null;
        $exception = null;
        try {
            $returnValue = $lambda();
        } catch (\Exception $e) {
            $exception = $e;
        }
        return [$returnValue, $exception];
    }

    private static function shouldMemorizeResult($returnPair, $memorizeExceptions = true)
    {
        if (!$memorizeExceptions && self::isException($returnPair)) {
            return false;
        }
        return true;
    }

    private static function isException(array $returnPair)
    {
        list($value, $exception) = $returnPair;
        return !is_null($exception);
    }

    private static function returnResult(array $returnPair)
    {
        list($value, $exception) = $returnPair;
        if ($exception) {
            throw $exception;
        } else {
            return $value;
        }
    }
}
