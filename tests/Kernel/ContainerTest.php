<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel;

use Lemon\Kernel\Container;
use Lemon\Kernel\Exceptions\NotFoundException;
use Lemon\Tests\Kernel\Resources\IFoo;
use Lemon\Tests\Kernel\Resources\Units\Bar;
use Lemon\Tests\Kernel\Resources\Units\Baz;
use Lemon\Tests\Kernel\Resources\Units\Foo;
use Lemon\Tests\Kernel\Resources\Units\User;
use Lemon\Tests\Kernel\Resources\Units\UserFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ContainerTest extends TestCase
{
    public function testAddService()
    {
        $container = new Container();

        $container->add(Foo::class);
        $this->assertSame([Foo::class], $container->services());
        $container->add(Bar::class);
        $this->assertSame([Foo::class, Bar::class], $container->services());

        $this->expectException(NotFoundException::class);
        $container->add('Klobna');
    }

    public function testGetService()
    {
        $container = new Container();
        $container->add(Foo::class);
        $foo = $container->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertSame($foo, $container->get(Foo::class));

        $container->add(Bar::class);
        $this->assertInstanceOf(Bar::class, $container->get(Bar::class));

        $container = new Container();
        $this->expectException(NotFoundException::class);
        $container->get(Bar::class);
    }

    public function testHasService()
    {
        $container = new Container();
        $container->add(Foo::class);
        $this->assertTrue($container->has(Foo::class));
        $this->assertFalse($container->has('klobna'));
    }

    public function testAlias()
    {
        $container = new Container();
        $container->add(Foo::class);
        $container->alias('klobna', Foo::class);
        $this->assertSame($container->get(Foo::class), $container->get('klobna'));

        $container->alias(IFoo::class, Foo::class);
        $this->assertSame($container->get(Foo::class), $container->get(IFoo::class));
        $container->add(Baz::class);
        $this->assertInstanceOf(Baz::class, $container->get(Baz::class));

        $this->expectException(NotFoundException::class);
        $container->get('parek');
        $container->alias('rizek', Bar::class);
    }

    public function testCall()
    {
        $container = new Container();
        $container->add(Foo::class);
        $this->assertSame(3, $container->call(function ($bar, Foo $foo, $baz = 1) {
            return $bar + $baz;
        }, ['bar' => 2]));

        $this->assertSame(3, $container->call(function (Foo $foo, $bar, $baz = 1) {
            return $bar + $baz;
        }, ['bar' => 2]));

        $this->assertSame(3, $container->call(function () {
            \Fiber::suspend(3);

            return 4;
        }, []));
    }

    public function testIsInjectable()
    {
        $container = new Container();
        $this->assertTrue($container->isInjectable(User::class));
    }

    public function testInjectables()
    {
        $container = new Container();
        $container->add(UserFactory::class);

        $this->assertThat(
            $container->get(User::class, 'frajer'),
            $this->equalTo(new User(1, 'frajer'))
        );

        $this->assertThat(
            $container->call(fn (User $user) => $user, ['user' => 'frajer']),
            $this->equalTo(new User(1, 'frajer'))
        );
    }
}
