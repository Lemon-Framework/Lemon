<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\WhileDirective;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class WhileDirectiveTest extends TestCase
{
    public function testCompilation()
    {
        $d = new WhileDirective();
        $this->assertSame('while ($foo):', $d->compileOpenning('$foo', []));
        $this->assertSame('while ($foo):', $d->compileOpenning('$foo', ['if']));

        $this->assertThrowable(function (WhileDirective $d) {
            $d->compileOpenning('', []);
        }, CompilerException::class, $d);
    }
}
