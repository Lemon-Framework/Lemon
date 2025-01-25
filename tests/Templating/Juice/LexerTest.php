<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Position;
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
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(1, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(1, 2), '!DOCTYPE'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(1, 11), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(1, 15), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(2, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(2, 2), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(2, 7), 'lang'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Equals, new Position(2, 11), '='), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, new Position(2, 12), '"'), $lexer->next());
        $lexer->changeContext(Context::HtmlString);
        $this->assertEquals(new Token(HtmlTokenKind::StringContent, new Position(2, 13), 'en'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, new Position(2, 15), '"'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(2, 16), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(3, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(3, 2), 'body'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(3, 6), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(4, 5), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(4, 6), 'p'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(4, 7), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::Text, new Position(4, 8), 'foo+bar-baz::foo'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(4, 24), '</'), $lexer->peek());
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(4, 24), '</'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(4, 26), 'p'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(4, 27), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(5, 1), '</'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(5, 3), 'body'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(5, 7), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(6, 1), '</'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(6, 3), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(6, 7), '>'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(6, 7), '>'), $lexer->current());
        $this->assertNull($lexer->peek());
        $this->assertNull($lexer->next());

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
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(1, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(1, 2), '!DOCTYPE'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(1, 11), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(1, 15), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(2, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(2, 2), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(2, 7), 'lang'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::Equals, new Position(2, 11), '='), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, new Position(2, 12), '"'), $lexer->next());
        $lexer->changeContext(Context::HtmlString);
        $this->assertEquals(new Token(HtmlTokenKind::StringContent, new Position(2, 13), 'en'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::StringDelim, new Position(2, 15), '"'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(2, 16), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::TagOpen, new Position(3, 1), '<'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(3, 2), 'body'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(3, 6), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(JuiceTokenKind::DirectiveStart, new Position(4, 5), "foreach"), $lexer->next()); 
        $lexer->changeContext(Context::Juice);
        $this->assertEquals(new Token(PHPTokenKind::Variable, new Position(4, 15), "foo"), $lexer->next());        
        $this->assertEquals(new Token(PHPTokenKind::OpenningSquareBracket, new Position(4, 19), "["), $lexer->next());  
        $lexer->changeContext(Context::JuiceUnclosed);
        $this->assertEquals(new Token(PHPTokenKind::Number, new Position(4, 20), "1"), $lexer->next());       
        $this->assertEquals(new Token(PHPTokenKind::ClosingSquareBracket, new Position(4, 21), "]"), $lexer->next());
        $lexer->changeContext(Context::Juice);
        $this->assertEquals(new Token(PHPTokenKind::As, new Position(4, 23), "as"), $lexer->next());       
        $this->assertEquals(new Token(PHPTokenKind::Variable, new Position(4, 26), "bar"), $lexer->next());  
        $this->assertEquals(new Token(JuiceTokenKind::Closing, new Position(4, 31), "]"), $lexer->next());  
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(JuiceTokenKind::OutputStart, new Position(5, 9), "[["), $lexer->next());
        $lexer->changeContext(Context::Juice);
        $this->assertEquals(new Token(PHPTokenKind::Variable, new Position(5, 12), "bar"), $lexer->next());
        $this->assertEquals(new Token(PHPTokenKind::Operator, new Position(5, 17), "|>"), $lexer->next());
        $this->assertEquals(new Token(PHPTokenKind::Name, new Position(5, 20), "baz"), $lexer->next());       
        $this->assertEquals(new Token(JuiceTokenKind::Closing, new Position(5, 24), "]]"), $lexer->next());  
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(JuiceTokenKind::EndDirective, new Position(6, 5), "foreach"), $lexer->next());       
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(7, 1), '</'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(7, 3), 'body'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(7, 7), '>'), $lexer->next());
        $lexer->changeContext(Context::Html);
        $this->assertEquals(new Token(HtmlTokenKind::EndTagOpen, new Position(8, 1), '</'), $lexer->next());
        $lexer->changeContext(Context::HtmlTag);
        $this->assertEquals(new Token(HtmlTokenKind::Name, new Position(8, 3), 'html'), $lexer->next());
        $this->assertEquals(new Token(HtmlTokenKind::TagClose, new Position(8, 7), '>'), $lexer->next());

    } 

    // todo test multiline, escaping
}
