<?php

namespace Lemon\Tests\Kernel;

use Lemon\Kernel\Lifecycle;
use Lemon\Tests\Kernel\Resources\Units\Bar;
use Lemon\Tests\Kernel\Resources\Units\Foo;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LifecycleTest extends TestCase
{
    public Lifecycle $lifecycle;

    /**
     * @before
     */
    public function beforeEach()
    {
        $lifecycle = new Lifecycle(__DIR__);
        $lifecycle->addUnit('bar', Bar::class);
        $lifecycle->addUnit('foo', Foo::class);
        $this->lifecycle = $lifecycle;
    }

    public function testUnits()
    {
        $first = $this->lifecycle->unit('bar');

        $this->assertInstanceOf(Bar::class, $first);

        $this->assertSame($this->lifecycle->unit('bar'), $first);

        $this->assertSame($first, $this->lifecycle->bar);
    }
}
