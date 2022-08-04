<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class BlockTest extends TestCase
{
    public function testCompileOpenning()
    {
        $d = new DirectiveCompiler();
        $this->assertSame('<?php $_layout->startBlock(\'foo\') ?>', $d->compileOpenning(new Token(Token::TAG, ['block', '"foo"'], 1), []));

        $this->assertSame('<?php $_layout->startBlock(\'foo\') ?>', $d->compileOpenning(new Token(Token::TAG, ['block', '\'foo\''], 1), []));

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['block', ''], 1), []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['block', 'echo'], 1), []);
        }, CompilerException::class, $d);

        $this->assertThrowable(function (DirectiveCompiler $d) {
            $d->compileOpenning(new Token(Token::TAG, ['block', '"parek" "rizek"'], 1), []);
        }, CompilerException::class, $d);
    }

    public function testCompileClosing()
    {
        $d = new DirectiveCompiler();
        $this->assertSame('<?php $_layout->endBlock() ?>', $d->compileClosing('block'));
    }
}
