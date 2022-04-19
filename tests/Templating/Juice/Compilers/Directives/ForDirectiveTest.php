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
class ForDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php for ($foo = 0; $foo < 10; $foo++): ?>', $c->compileOpenning('for', '$foo = 0; $foo < 10; $foo++', []));
        $this->assertSame('<?php for ($foo = 0; $foo < 10; $foo++): ?>', $c->compileOpenning('for', '$foo = 0; $foo < 10; $foo++', ['if']));

        $this->assertThrowable(function (DirectiveCompiler $c) {
            $c->compileOpenning('for', '', []);
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
