<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\HtmlTokenKind;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Tests\TestCase;

class LexerTest extends TestCase
{
    private function getLexer(): Lexer 
    {
        return new Lexer(new Syntax());
    }

    public function testHtmlParsing(): void
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
        $this->assertThat(iterator_to_array($lexer->lex($content, Context::Html)), $this->equalTo([
            new Token(HtmlTokenKind::TagOpen, 1, 1, '<'),
            new Token(HtmlTokenKind::Text, 1, 2, '!'),
            new Token(HtmlTokenKind::Text, 1, 3, 'DOCTYPE'),
            new Token(HtmlTokenKind::Space, 1, 10, ' '),
            new Token(HtmlTokenKind::Text, 1, 11, 'html'),
            new Token(HtmlTokenKind::TagClose, 1, 15, '>'),
            new Token(HtmlTokenKind::Space, 2, 0, "\n"),
            new Token(HtmlTokenKind::TagOpen, 2, 1, '<'),
            new Token(HtmlTokenKind::Text, 2, 2, 'html'),
            new Token(HtmlTokenKind::Space, 2, 6, ' '),
            new Token(HtmlTokenKind::Text, 2, 7, 'lang'),
            new Token(HtmlTokenKind::Equals, 2, 11, '='),
            new Token(HtmlTokenKind::StringDelim, 2, 12, '"'),
            new Token(HtmlTokenKind::Text, 2, 13, 'en'),
            new Token(HtmlTokenKind::StringDelim, 2, 15, '"'),
            new Token(HtmlTokenKind::TagClose, 2, 16, '>'),
            new Token(HtmlTokenKind::Space, 3, 0, "\n"),
            new Token(HtmlTokenKind::TagOpen, 3, 1, '<'),
            new Token(HtmlTokenKind::Text, 3, 2, 'body'),
            new Token(HtmlTokenKind::TagClose, 3, 6, '>'),
            new Token(HtmlTokenKind::Space, 4, 0, "\n"),
            new Token(HtmlTokenKind::Space, 4, 1, "    "),
            new Token(HtmlTokenKind::TagOpen, 4, 5, '<'),
            new Token(HtmlTokenKind::Text, 4, 6, 'p'),
            new Token(HtmlTokenKind::TagClose, 4, 7, '>'),
            new Token(HtmlTokenKind::Text, 4, 8, 'foo'),
            new Token(HtmlTokenKind::Text, 4, 11, '+'),
            new Token(HtmlTokenKind::Text, 4, 12, 'bar'),
            new Token(HtmlTokenKind::Text, 4, 15, '-'),
            new Token(HtmlTokenKind::Text, 4, 16, 'baz'),
            new Token(HtmlTokenKind::Text, 4, 19, '::'),
            new Token(HtmlTokenKind::Text, 4, 21, 'foo'),
            new Token(HtmlTokenKind::EndTagOpen, 4, 24, '</'),
            new Token(HtmlTokenKind::Text, 4, 26, 'p'),
            new Token(HtmlTokenKind::TagClose, 4, 27, '>'),
            new Token(HtmlTokenKind::Space, 5, 0, "\n"),
            new Token(HtmlTokenKind::EndTagOpen, 5, 1, '</'),
            new Token(HtmlTokenKind::Text, 5, 3, 'body'),
            new Token(HtmlTokenKind::TagClose, 5, 7, '>'),
            new Token(HtmlTokenKind::Space, 6, 0, "\n"),
            new Token(HtmlTokenKind::EndTagOpen, 6, 1, '</'),
            new Token(HtmlTokenKind::Text, 6, 3, 'html'),
            new Token(HtmlTokenKind::TagClose, 6, 7, '>'),
        ]));

    }
}
