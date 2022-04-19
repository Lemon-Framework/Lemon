<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Tests\TestCase;

class ElseDirective extends TestCase
{
    public function testCompilation()
    {
        $d = new ElseDirective();
        $this->assertSame('else:', $d->compileOpenning)
    }
}
