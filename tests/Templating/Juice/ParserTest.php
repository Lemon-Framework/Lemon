<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Parser;
use Lemon\Templating\Juice\Token;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ParserTest extends TestCase
{
    public function testParsingTags()
    {
        $p = $this->getParser([
            new Token(Token::TAG, ['foreach', '$foo as $bar']),
            new Token(Token::TAG_END, 'foreach'),
        ]);
        $this->assertSame('<?php foreach ($foo as $bar): ?><?php endforeach ?>', $p->parse());
    }

    private function getParser(array $tokens)
    {
        return new Parser($tokens, new OutputCompiler(), new DirectiveCompiler());
    }
}
