<?php

declare(strict_types=1);

namespace Lemon\Tests;

use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Throwable;

class TestCase extends FrameworkTestCase
{
    protected function assertThrowable(callable $action, string $expected, mixed ...$args)
    {
        $thrown = false;
        try {
            $action(...$args);
        } catch (Throwable $actual) {
            if ($actual instanceof $expected) {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown, 'Failed asserting that action throws '.$expected.'.');
    }
}
