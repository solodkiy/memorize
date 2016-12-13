<?php


namespace Solodkiy\Memorize;

use InvalidArgumentException;

class Utils
{
    /**
     * @param mixed $item
     * @return string
     */
    public static function hash($item)
    {
        return md5(self::stringify($item));
    }

    public static function stringify($item)
    {
        if (is_resource($item)) {
            throw new \RuntimeException('You cannot stringify resource');
        }
        if ($item instanceof \Closure) {
            return self::stringifyClosure($item);
        }

        if (is_scalar($item) || is_null($item)) {
            return serialize($item);
        } elseif (is_array($item)) {
            $result = 'a:';
            foreach ($item as $key => $value) {
                $result .= self::stringify($key).'-'.self::stringify($value).',';
            }
            return $result;
        } elseif (is_object($item)) {
            if (method_exists($item, 'hash')) {
                return get_class($item).':'.$item->hash();
            } else {
                throw new \RuntimeException('Only objects with method hash might be stringify');
            }
        }

        throw new \Exception('Unknown type '.gettype($item));
    }

    private static function stringifyClosure(\Closure $item)
    {
        $reflection = new \ReflectionFunction($item);
        return 'c:'.$reflection->getFileName().'-'.$reflection->getStartLine().'-'.$reflection->getEndLine();
    }

    //------------------------
    // Asserts from webmozart

    public static function assertString($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf(
                'Expected a string. Got: %s',
                self::typeToString($value)
            ));
        }
    }

    private static function typeToString($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
