Memorize provides simple in-var cache for closures. It can be used to create lazy functions. 
Function takes closure and optional argument paramsHash.
If the closure with the same arguments was run before memorize will return result from cache without the closure call. At the first call result will be calculated and stored in cache.
Cache is stored in global space for static methods and simple functions. For closures defined in object method cache will be stored in this object (two objects have different cache).
By design cache is stored only while script is running. There is no way to set ttl for cache or invalidate it.

Memorize automatically calculates paramsHash for closures with scalar arguments. If your closure has objects as arguments you must calculate and pass paramsHash as the second argument. (see \Solodkiy\Memorize\Memorize\MemorizeInObjectTest::correctAddDaysToTime test)

Notice that memorize calculates closure hash by file name, start line and end line of closure declaration. Memorize will not work correctly if you declare two closures in one line (e.g: after code obfuscation).

Examples
--------

### Singleton with memorize
Before:
```
class Singleton
{
    /**
     * @var Singleton
     */
    private static $instance;
    
    private function __construct()
    {
        // private
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Singleton();
        }
        return self::$instance;
    }
}
```
After:
```
class Singleton
{
    private function __construct()
    {
        // private
    }

    public static function getInstance()
    {
        return memorize(function() {
            return new Singleton();
        });
    }
}
```

### Lazy recursive factorial function
```
function factorial($number)
{
    return memorize(function () use ($number) {
        if ($number <= 0) {
            throw new \InvalidArgumentException('Number must be natural');
        }
        return ($number == 1)
            ? 1
            : $number * factorial($number - 1);
    });
}

```
See MemorizeInFuncitonTest

### Also it correct works in objects. 
Before:
```
class TableGateway
{
    /**
     * @var array
     */ 
    private $cacheStatistic = [];
    
    public function getA($accountId)
    {
        return $this->calculateStatistic($accountId)['a'];
    }
    
    public function getB($accountId)
    {
        return $this->calculateStatistic($accountId)['b'];
    }
    
    private function calculateStatistic($accountId)
    {
        if (!isset($this->cacheStatistic[$accountId])) {
            $sql = 'SELECT AVG(price) AS a ...';
            $result = $this->db->getRows($sql, [$accountId]);
            $this->cacheStatistic[$accountId] = $result;
        }
        return $this->cacheStatistic[$accountId];
    }
}
```
After:
```
class TableGateway
{
    public function getA($accountId)
    {
        return $this->calculateStatistic($accountId)['a'];
    }
    
    public function getB($accountId)
    {
        return $this->calculateStatistic($accountId)['b'];
    }
    
    private function calculateStatistic($accountId)
    {
        return memorize(function () use ($accountId) {
            $sql = 'SELECT AVG(price) AS a ...';
            return $this->db->getRows($sql, [$accountId]);
        });
    }
}

```
See \Solodkiy\Memorize\Memorize\MemorizeFunctionInObjectTest::testTwoObjects