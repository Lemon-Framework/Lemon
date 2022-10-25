<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function testCompose()
    {
        $this->assertSame('Foo', compose('ucfirst', 'strtolower')('FOO'));
    }
}
