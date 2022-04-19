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
class ForeachDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php foreach ($foo as $bar): ?>', $c->compileOpenning('foreach', '$foo as $bar', []));
        $this->assertSame('<?php foreach ($foo as $bar): ?>', $c->compileOpenning('foreach', '$foo as $bar', ['if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('foreach', '', []);
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
