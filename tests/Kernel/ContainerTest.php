<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel;

use Lemon\Exceptions\ContainerException;
use Lemon\Kernel\Container;
use Lemon\Tests\Kernel\Resources\Units\Bar;
use Lemon\Tests\Kernel\Resources\Units\Foo;
use PHPUnit\Framework\TestCase;

class ContainerTests extends TestCase
{
    public function testAddService()
    {
        $container = new Container();

        $container->addService(Foo::class);
        $this->assertSame([Foo::class], $container->getAllServices());
        $container->addService(Bar::class);
        $this->assertSame([Foo::class, Bar::class], $container->getAllServices());

        $this->expectException(ContainerException::class);
        $container->addService(Foo::class);
        $container->addService('Klobna');
    }

    public function testGetService()
    {
        $container = new Container();
        $container->addService(Foo::class);
        $foo = $container->getService(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertSame($foo, $container->getService(Foo::class));

        $container->addService(Bar::class);
        $this->assertInstanceOf(Bar::class, $container->getService(Bar::class));

        $container = new Container();
        $this->expectException(ContainerException::class);
        $container->getService(Bar::class);
    }

}
