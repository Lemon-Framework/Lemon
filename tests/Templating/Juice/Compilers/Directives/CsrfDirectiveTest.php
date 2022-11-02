<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\Directives\CsrfDirective;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CsrfDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new CsrfDirective();
        $this->assertSame('<input type="hidden" name="CSRF_TOKEN" value="<?php echo \Lemon\Csrf::getToken() ?>">', $c->compileOpenning(new Token(Token::TAG, ['csrf', ''], 1), []));
        $this->expectException(CompilerException::class);
        $c->compileOpenning(new Token(Token::TAG, ['csrf', 'parek'], 1), []);
    }
}
