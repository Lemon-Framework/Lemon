<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token;
use PHPUnit\Framework\TestCase;

/**
 * Theese tests are testing lexer along with default syntax.
 */
class LexerTest extends TestCase
{
    public function getLexer(): Lexer
    {
        return new Lexer(new Syntax());
    }
    
    public function testLexingTags()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{foreach something as [$foo => $bar]}{endforeach}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as [$foo => $bar]']),
            new Token(Token::TAG_END, 'foreach'),
        ]));

        $tokens = $lexer->lex('{   foreach something as something   }{           endforeach      }');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as something']),
            new Token(Token::TAG_END, 'foreach'),
        ]));
    }

    public function testLexingNestedTags()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{foreach something as [$foo => $bar]}{if $foo == $bar}{endif}{endforeach}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as [$foo => $bar]']),
            new Token(Token::TAG, ['if', '$foo == $bar']),
            new Token(Token::TAG_END, 'if'),
            new Token(Token::TAG_END, 'foreach'),
        ]));
    }

    public function testLexingEcho()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{{$foo}}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::OUTPUT, '$foo'),
        ]));      

        $tokens = $lexer->lex('{{    $foo                }}{{ klobna("sth")}}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::OUTPUT, '$foo'),
            new Token(Token::OUTPUT, 'klobna("sth")'),
        ]));       
    }

    public function testLexingUnescaped()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{!$foo!}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::UNESCAPED, '$foo'),
        ]));     

        $tokens = $lexer->lex('{! $foo    !}{!SOMETHINGUNSAFE($foo) !}{! ok!}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::UNESCAPED, '$foo'),
            new Token(Token::UNESCAPED, 'SOMETHINGUNSAFE($foo)'),
            new Token(Token::UNESCAPED, 'ok'),
        ]));      
    }

    public function testLexingText()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex(<<<'HTML'
            <h1>foo</h1>
            <script>alert('hello')</script>
            <p>ok</p>
        HTML);
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TEXT, <<<'HTML'
                <h1>foo</h1>
                <script>alert('hello')</script>
                <p>ok</p>
            HTML),
        ]));            
    }

    public function testLexingComments()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{# klobna #}');
        $this->assertEmpty($tokens);
    }

    public function testLexing()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex(<<<'HTML'
            <h1>{{ $foo }}</h1>
            { foreach $users as $user}
               <h2>{{ $user }} </h2>
                {! md($user->description) !}
            {endforeach }
        HTML);
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TEXT, '    <h1>'),
            new Token(Token::OUTPUT, '$foo'),
            new Token(Token::TEXT, '</h1>'.PHP_EOL.'    '),
            new Token(Token::TAG, ['foreach', '$users as $user']),
            new Token(Token::TEXT, PHP_EOL.'       <h2>'),
            new Token(Token::OUTPUT, '$user'),
            new Token(Token::TEXT, ' </h2>'.PHP_EOL.'        '),
            new Token(Token::UNESCAPED, 'md($user->description)'),
            new Token(Token::TEXT, PHP_EOL.'    '),
            new Token(Token::TAG_END, 'foreach'),
        ]));      
    }
}
