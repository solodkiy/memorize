<?php


namespace Solodkiy\Memorize;


use Doctrine\Instantiator\Exception\InvalidArgumentException;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testHasAndSet()
    {
        $storage = new Storage();
        $this->assertFalse($storage->has('item1', ''));
        $this->assertFalse($storage->has('item1', 'param1'));
        $this->assertFalse($storage->has('item2', ''));

        $storage->set('item1', '', 'value');

        $this->assertTrue($storage->has('item1', ''));
        $this->assertFalse($storage->has('item2', ''));
        $this->assertFalse($storage->has('item1', 'param1'));

        $storage->set('item1', 'param1', 'value');

        $this->assertTrue($storage->has('item1', ''));
        $this->assertFalse($storage->has('item2', ''));
        $this->assertTrue($storage->has('item1', 'param1'));
    }

    public function testGetSimple()
    {
        $storage = new Storage();
        $storage->set('item1', 'param1', 'value');
        $this->assertEquals('value', $storage->get('item1', 'param1'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testHasParamHashInt()
    {
        $storage = new Storage();
        $storage->has('item1', 100);
        /*
        $this->assertFalse($storage->has('item1', 100));
        $storage->set('item1', 100, 'value');
        $this->assertTrue($storage->has('item1', 100));
        $this->assertFalse($storage->has('item1', 101));
        */

    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetNotExists()
    {
        $storage = new Storage();
        $this->assertEquals('value', $storage->get('item1', ''));
    }

}
