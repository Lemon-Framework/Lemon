<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\SwitchDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

class SwitchDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new SwitchDirective();
        $this->assertSame('switch ($foo):', $d->compileOpenning('$foo', []));
        $this->assertSame('switch ($foo):', $d->compileOpenning('$foo', ['if']));

        $this->assertThrowable(function(SwitchDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);
    }
}
