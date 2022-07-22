<?php

declare(strict_types=1);

namespace Lemon\Tests\Types;

use Exception;
use Lemon\Support\Types\Str;
use Lemon\Support\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class StrTest extends TestCase
{
    public function testCalling()
    {
        $this->assertSame('mixer', Str::reverse('rexim')->value);
        $this->assertSame('krokodyl', Str::from('krokodyl')->value);
        $this->assertInstanceOf(String_::class, Str::from('krokodyl'));
        $this->expectException(Exception::class);
        Str::lokomotivize();
    }
}
