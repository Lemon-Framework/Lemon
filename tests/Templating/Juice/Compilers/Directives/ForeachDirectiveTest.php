<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\ForeachDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

class ForeachDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new ForeachDirective();
        $this->assertSame('foreach ($foo as $bar):', $d->compileOpenning('$foo as $bar', []));
        $this->assertSame('foreach ($foo as $bar):', $d->compileOpenning('$foo as $bar', ['if']));

        $this->assertThrowable(function(ForeachDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);
    }
}
