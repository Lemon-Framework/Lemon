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
class ForeachDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php foreach ($foo as $bar): ?>', $c->compileOpenning(new T(T::TAG, ['foreach', '$foo as $bar'], 1), []));
        $this->assertSame('<?php foreach ($foo as $bar): ?>', $c->compileOpenning(new T(T::TAG, ['foreach', '$foo as $bar'], 1), ['if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning(new T(T::TAG, ['foreach', ''], 1), []);
        }, CompilerException::class, $c);
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('foreach'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endforeach ?>', $c->compileClosing('foreach'));
    }
}
