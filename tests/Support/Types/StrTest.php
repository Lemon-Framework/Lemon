<?php

namespace Lemon\Tests\Types;

use Exception;
use Lemon\Support\Types\Str;
use Lemon\Support\Types\String_;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testCalling()
    {
        $this->assertSame('mixer', Str::reverse('rexim')->content);
        $this->assertSame('krokodyl', Str::from('krokodyl')->content);
        $this->assertInstanceOf(String_::class, Str::from('krokodyl'));
        $this->expectException(Exception::class);
        Str::lokomotivize();
    }
}
