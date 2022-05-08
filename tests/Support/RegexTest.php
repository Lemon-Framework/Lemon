<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Support\Regex;
use Lemon\Tests\TestCase;

class RegexTest extends TestCase
{
    public function testPosition()
    {
        $this->assertSame(3, Regex::getLine("foo\nb\nar", 6));
        $this->assertSame(1, Regex::getLine("foo\nb\nar", 0));
    }
}
