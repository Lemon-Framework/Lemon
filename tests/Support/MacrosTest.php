<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Support\Macros;
use Lemon\Tests\TestCase;

class MacrosTest extends TestCase
{
    public function testMacros()
    {
        $foo = new Foo(2);
        $foo->macro('add', function() {
            return $this->getNumber() + 1;
        });

        $this->assertSame(3, $foo->add());
    }
}

class Foo
{
    use Macros;

    public function __construct(
        private int $number
    ) {

    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
