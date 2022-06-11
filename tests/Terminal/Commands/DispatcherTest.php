<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\Commands;

use Lemon\Terminal\Commands\Command;
use Lemon\Terminal\Commands\Dispatcher;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DispatcherTest extends TestCase
{
    public function testDispatching()
    {
        $d = new Dispatcher();
        $fn = function ($bar) {
            echo $bar;
        };
        $d->add(new Command('foo {bar}', $fn));

        $this->assertSame([$fn, ['bar' => 'baz']], $d->dispatch(['foo', 'bar=baz']));

        $this->assertSame('Command parek was not found.', $d->dispatch(['parek']));

        $d->add(new Command('baz {bar} {foo?} {idk}', $fn));
        $this->assertSame([$fn, ['bar' => 'foo', 'idk' => '10']], $d->dispatch(['baz', 'idk=10', 'bar=foo']));

        $d->add(new Command('bar {bar} {foo?} {idk}', $fn));
        $this->assertSame('Argument bar is missing.', $d->dispatch(['bar']));
    }
}
