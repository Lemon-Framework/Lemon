<?php

declare(strict_types=1);

namespace Lemon\Tests\Support\Types;

use Lemon\Support\Types\Type;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TypeTest extends TestCase
{
    public function testIs()
    {
        $this->assertTrue(Type::is('mixed', 'foo'));
        $this->assertTrue(Type::is('string', 'foo'));
        $this->assertFalse(Type::is('string', 10));
        $this->assertTrue(Type::is('object', new Type()));
        $this->assertTrue(Type::is(Type::class, new Type()));
    }
}
