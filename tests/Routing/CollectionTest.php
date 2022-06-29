<?php

declare(strict_types=1);

namespace Lemon\Tests\Routing;

use Lemon\Kernel\Container;
use Lemon\Routing\Collection;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CollectionTest extends TestCase
{
    public function testAdding()
    {
        $collection = new Collection(new Container());
        $route = $collection->add('/rizek/', 'get', fn() => 'foo');
        $this->assertSame([
            'rizek' => $route,
        ], $collection->routes());
        
        $collection->add('/rizek', 'post', fn() => 'foo');
        $this->assertSame([
            'rizek' => $route,
        ], $collection->routes());
    }

    public function testFinding()
    {

    }

    public function testHas()
    {
        $collection = new Collection(new Container());
        $route = $collection->add('/rizek', 'get', fn() => 'foo');
        $this->assertTrue($collection->has('rizek'));
        $this->assertTrue($collection->has('rizek'));
        $this->assertFalse($collection->has('nevim'));
    }
}
