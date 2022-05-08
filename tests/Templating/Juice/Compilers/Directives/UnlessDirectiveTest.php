<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token as T;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class UnlessDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php if (!(1 == $foo)): ?>', $c->compileOpenning(new T(T::TAG, ['unless', '1 == $foo'], 1), []));
        $this->assertSame('<?php if (!(1 == $foo)): ?>', $c->compileOpenning(new T(T::TAG, ['unless', '1 == $foo'], 1), ['switch']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['unless', ''], 1), []);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('unless'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endif ?>', $c->compileClosing('unless'));
    }
}
