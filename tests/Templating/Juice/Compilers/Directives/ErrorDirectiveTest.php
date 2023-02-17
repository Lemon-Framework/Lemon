<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Compilers\Directives\ErrorDirective;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ErrorDirectiveTest extends TestCase
{
    public function testOpen()
    {
        $c = new ErrorDirective();
        $this->assertSame('<?php echo \Lemon\Validator::error() ?>', $c->compileOpenning(new Token(Token::TAG, ['error', ''], 1), []));
    }
}
