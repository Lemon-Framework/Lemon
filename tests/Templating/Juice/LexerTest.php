<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\HtmlTokenKind;
use Lemon\Templating\Juice\Token\JuiceTokenKind;
use Lemon\Templating\Juice\Token\PHPTokenKind;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Tests\TestCase;

class LexerTest extends TestCase
{
    private function getLexer(string $content): Lexer 
    {
        return new Lexer(new Syntax(), $content);
    }

    public function testHtmlLexing(): void
    {
        $lexer = $this->getLexer(<<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    <p>foo+bar-baz::foo</p>
</body>
</html>
HTML);
        // damn
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 1, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 2, '!'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 3, 'DOCTYPE'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 1, 10, ' '), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 11, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 1, 15, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 2, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 2, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 2, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 2, 6, ' '), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 7, 'lang'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Equals, 2, 11, '='), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, 2, 12, '"'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 13, 'en'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, 2, 15, '"'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 2, 16, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 3, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 3, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 3, 2, 'body'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 3, 6, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 4, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 4, 1, "    "), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 4, 5, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 6, 'p'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 4, 7, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 8, 'foo'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 11, '+'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 12, 'bar'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 15, '-'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 16, 'baz'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 19, '::'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 21, 'foo'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, 4, 24, '</'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 4, 26, 'p'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 4, 27, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 5, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, 5, 1, '</'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 5, 3, 'body'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 5, 7, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 6, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, 6, 1, '</'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 6, 3, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 6, 7, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 6, 7, '>'), $lexer->current());
        $this->assertNull($lexer->next(Context::Html));

    }

    public function testJuiceLexing(): void
    {
        $lexer = $this->getLexer($content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<body>
    [ foreach \$foo[1] as \$bar ]
        [[ \$bar |> baz ]]
    [ /foreach ]
</body>
</html>
HTML);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 1, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 2, '!'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 3, 'DOCTYPE'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 1, 10, ' '), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 1, 11, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 1, 15, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 2, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 2, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 2, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 2, 6, ' '), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 7, 'lang'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Equals, 2, 11, '='), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, 2, 12, '"'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 2, 13, 'en'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, 2, 15, '"'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 2, 16, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 3, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, 3, 1, '<'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 3, 2, 'body'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 3, 6, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 4, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 4, 1, "    "), $lexer->next(Context::Html)); 
        $this->assertEquals(new Token(JuiceTokenKind::DirectiveStart, 4, 5, "foreach"), $lexer->next(Context::Html));        
        $this->assertEquals(new Token(PHPTokenKind::Variable, 4, 15, "foo"), $lexer->next(Context::Juice));        
        $this->assertEquals(new Token(PHPTokenKind::OpenningSquareBracket, 4, 19, "["), $lexer->next(Context::Juice));       
        $this->assertEquals(new Token(PHPTokenKind::Number, 4, 20, "1"), $lexer->next(Context::JuiceUnclosed));       
        $this->assertEquals(new Token(PHPTokenKind::ClosingSquareBracket, 4, 21, "]"), $lexer->next(Context::JuiceUnclosed));        
        $this->assertEquals(new Token(PHPTokenKind::As, 4, 23, "as"), $lexer->next(Context::Juice));       
        $this->assertEquals(new Token(PHPTokenKind::Variable, 4, 26, "bar"), $lexer->next(Context::Juice));  
        $this->assertEquals(new Token(JuiceTokenKind::Closing, 4, 31, "]"), $lexer->next(Context::Juice));  
        $this->assertEquals(new Token(HtmlTokenKind::Space, 5, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 5, 1, "        "), $lexer->next(Context::Html));
        $this->assertEquals(new Token(JuiceTokenKind::OutputStart, 5, 9, "[["), $lexer->next(Context::Html));
        $this->assertEquals(new Token(PHPTokenKind::Variable, 5, 12, "bar"), $lexer->next(Context::Juice));
        $this->assertEquals(new Token(PHPTokenKind::Operator, 5, 17, "|>"), $lexer->next(Context::Juice));
        $this->assertEquals(new Token(PHPTokenKind::Name, 5, 20, "baz"), $lexer->next(Context::Juice));       
        $this->assertEquals(new Token(JuiceTokenKind::Closing, 5, 24, "]]"), $lexer->next(Context::Juice));       
        $this->assertEquals(new Token(HtmlTokenKind::Space, 6, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 6, 1, "    "), $lexer->next(Context::Html));
        $this->assertEquals(new Token(JuiceTokenKind::EndDirective, 6, 5, "foreach"), $lexer->next(Context::Html));       
        $this->assertEquals(new Token(HtmlTokenKind::Space, 7, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, 7, 1, '</'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 7, 3, 'body'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 7, 7, '>'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Space, 8, 0, "\n"), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, 8, 1, '</'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::Text, 8, 3, 'html'), $lexer->next(Context::Html));
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, 8, 7, '>'), $lexer->next(Context::Html));

    } 

    // todo test multiline
}
