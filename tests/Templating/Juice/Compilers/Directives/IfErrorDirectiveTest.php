<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class IfErrorDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php if (\Lemon\Validator::hasError()): ?>', $c->compileOpenning(new Token(Token::TAG, ['ifError', ''], 1), []));
    }

    public function testClosability()
    {
        $c = new DirectiveCompiler();
        $this->assertTrue($c->isClosable('ifError'));
    }

    public function testClose()
    {
        $c = new DirectiveCompiler();
        $this->assertSame('<?php endif ?>', $c->compileClosing('ifError'));
    }
}
