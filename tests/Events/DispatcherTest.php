<?php

declare(strict_types=1);

namespace Lemon\Tests\Events;

use Lemon\Events\Dispatcher;
use Lemon\Kernel\Application;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DispatcherTest extends TestCase
{
    public function testRegistration()
    {
        $events = new Dispatcher(new Application(__DIR__));
        $events->on('foo', function () {});
        $events->on('foo', function () {});
        $events->on('bar', function () {});
        $events->on('baz', function () {});
        $this->assertSame(['foo', 'bar', 'baz'], array_keys($events->events()));
    }

    public function testFiring()
    {
        $lc = new Application(__DIR__);
        $lc->add(Logger::class);
        $events = new Dispatcher($lc);
        $events->on('foo', function (Logger $logger) {
            $logger->log('foo');
        });
        $events->on('foo', function (Logger $logger) {
            $logger->log('foo 2');
        });

        $events->fire('foo');
        $this->assertSame(['foo', 'foo 2'], $lc->get(Logger::class)->read());

        $events->fire('foo');
        $this->assertSame(['foo', 'foo 2', 'foo', 'foo 2'], $lc->get(Logger::class)->read());
    }
}

class Logger
{
    private array $logs = [];

    public function log(string $message): static
    {
        $this->logs[] = $message;

        return $this;
    }

    public function read(): array
    {
        return $this->logs;
    }
}
