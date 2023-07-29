<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\Directives\TranslationDirective;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TranslationDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new TranslationDirective();
        $this->assertSame('<?php echo \Lemon\Translator::text(\'klobas\') ?>', $c->compileOpenning(new Token(Token::TAG, ['text', 'klobas'], 1), []));
        $this->assertSame('<?php echo \Lemon\Translator::text(\'klobas\') ?>', $c->compileOpenning(new Token(Token::TAG, ['text', '"klobas"'], 1), []));
        $this->assertSame('<?php echo \Lemon\Translator::text(\'klobas\') ?>', $c->compileOpenning(new Token(Token::TAG, ['text', "'klobas'"], 1), []));
        $this->expectException(CompilerException::class);
        $c->compileOpenning(new Token(Token::TAG, ['text', ''], 1), []);
    }
}
