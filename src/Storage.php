<?php


namespace Solodkiy\Memorize;

use RuntimeException;

class Storage
{
    private $data = [];

    public function set($name, $paramsHash, $value)
    {
        Utils::assertString($name);
        Utils::assertString($paramsHash);

        $this->data[$name][$paramsHash] = $value;
    }

    public function has($name, $paramsHash)
    {
        list($find, $value) = $this->find($name, $paramsHash);

        return $find;
    }

    public function get($name, $paramsHash)
    {
        list($find, $value) = $this->find($name, $paramsHash);
        if ($find) {
            return $value;
        } else {
            throw new RuntimeException('Item not found');
        }
    }

    private function find($name, $paramsHash)
    {
        Utils::assertString($name);
        Utils::assertString($paramsHash);
        if (array_key_exists($name, $this->data)) {
            if (array_key_exists($paramsHash, $this->data[$name])) {
                $value = $this->data[$name][$paramsHash];
                return [true, $value];
            }
        }
        return [false, null];
    }


}