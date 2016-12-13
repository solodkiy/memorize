<?php

namespace Solodkiy\Memorize\Support;

class Singleton
{
    private function __construct()
    {
        // private
    }

    public static function getInstance()
    {
        return memorize(function () {
            return new Singleton();
        });
    }
}
