<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token as T;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ForDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php for ($foo = 0; $foo < 10; $foo++): ?>', $c->compileOpenning(new T(T::TAG, ['for', '$foo = 0; $foo < 10; $foo++'], 1), []));
        $this->assertSame('<?php for ($foo = 0; $foo < 10; $foo++): ?>', $c->compileOpenning(new T(T::TAG, ['for', '$foo = 0; $foo < 10; $foo++'], 1), ['if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['for', ''], 1), []);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('for'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endfor ?>', $c->compileClosing('for'));
    }
}
