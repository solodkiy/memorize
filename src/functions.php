<?php

use Solodkiy\Memorize\Memorizator;
use Solodkiy\Memorize\Storage;
use Solodkiy\Memorize\Utils;

function memorize(Closure $lambda, $paramsHash = null) {

    $getStorage = function (Closure $lambda) {
        $reflection = new ReflectionFunction($lambda);
        $that = $reflection->getClosureThis();
        if ($that) {
            if (!isset($that->_memorizeStorage)) {
                $that->_memorizeStorage = new Storage();
            }
            return $that->_memorizeStorage;
        } else {
            global $_globalMemorizeStorage;
            if (is_null($_globalMemorizeStorage)) {
                $_globalMemorizeStorage = new Storage();
            }
            return $_globalMemorizeStorage;
        }
    };

    if (is_null($paramsHash)) {
        $reflection = new ReflectionFunction($lambda);
        $paramsHash = Utils::hash($reflection->getStaticVariables());
    }
    $contextName = Utils::stringify($lambda);

    return Memorizator::memorize($contextName, $lambda, $paramsHash, $getStorage($lambda));
}


