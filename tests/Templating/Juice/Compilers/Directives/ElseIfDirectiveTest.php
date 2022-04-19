<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ElseIfDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php elseif (10 == $foo): ?>', $c->compileOpenning('elseif', '10 == $foo', ['switch', 'if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('elseif', '', ['if']);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('elseif', '10 == $foo', []);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('elseif', '10 == $foo', ['if', 'switch']);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertFalse($c->isClosable('elseif'));
    }
}
