<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Token as T;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ElseIfDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php elseif (10 == $foo): ?>', $c->compileOpenning(new T(T::TAG, ['elseif', '10 == $foo'], 1), ['switch', 'if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['elseif', ''], 1), ['if']);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['elseif', '10 == $foo'], 1), []);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['elseif', '10 == $foo'], 1), ['if', 'switch']);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertFalse($c->isClosable('elseif'));
    }
}
