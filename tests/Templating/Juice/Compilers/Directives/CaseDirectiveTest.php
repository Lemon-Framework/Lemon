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
class CaseDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php case 10: ?>', $c->compileOpenning(new T(T::TAG, ['case', '10'], 1), ['if', 'switch']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['case', ''], 1), ['switch']);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['case', '10'], 1), []);
        }, CompilerException::class, $c);

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['case', '10'], 1), ['switch', 'if']);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertFalse($c->isClosable('case'));
    }
}
