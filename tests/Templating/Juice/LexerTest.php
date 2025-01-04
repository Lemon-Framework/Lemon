<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Templating\Juice\Token\TokenKind;
use Lemon\Tests\TestCase;

class LexerTest extends TestCase
{
    private function getLexer(): Lexer 
    {
        return new Lexer(new Syntax());
    }

    public function testHtmlParsing()
    {
        $lexer = $this->getLexer();
        $content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    <p>foo+bar-baz::foo</p>
</body>
</html>
HTML;
        $this->assertThat(iterator_to_array($lexer->lex($content)), $this->equalTo([
            new Token(TokenKind::HtmlTagOpen, 1, 0, ''),
            new Token(TokenKind::UnaryOperator, 1, 1, ''),
            new Token(TokenKind::Text, 1, 2, 'DOCTYPE'),
        ]));

    }
}
