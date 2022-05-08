<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Token as T;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SwitchDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php switch ($foo): ?>', $c->compileOpenning(new T(T::TAG, ['switch', '$foo'], 1), []));
        $this->assertSame('<?php switch ($foo): ?>', $c->compileOpenning(new T(T::TAG, ['switch', '$foo'], 1), ['if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['switch', ''], 1), []);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('switch'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endswitch ?>', $c->compileClosing('switch'));
    }
}
