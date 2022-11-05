<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Lexer;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token;
use PHPUnit\Framework\TestCase;

/**
 * Theese tests are testing lexer along with default syntax.
 *
 * @internal
 *
 * @coversNothing
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
            new Token(Token::TAG, ['foreach', 'something as [$foo => $bar]'], 1),
            new Token(Token::TAG_END, 'foreach', 1),
        ]));

        $tokens = $lexer->lex('{   foreach     something as something   }{           endforeach      }');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as something'], 1),
            new Token(Token::TAG_END, 'foreach', 1),
        ]));

        $tokens = $lexer->lex('{   foreach     something as something   }{           /foreach      }');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as something'], 1),
            new Token(Token::TAG_END, 'foreach', 1),
        ]));
    }

    public function testLexingNestedTags()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{foreach something as [$foo => $bar]}{if $foo == $bar}{endif}{endforeach}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TAG, ['foreach', 'something as [$foo => $bar]'], 1),
            new Token(Token::TAG, ['if', '$foo == $bar'], 1),
            new Token(Token::TAG_END, 'if', 1),
            new Token(Token::TAG_END, 'foreach', 1),
        ]));
    }

    public function testLexingEcho()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{{$foo}}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::OUTPUT, '$foo', 1),
        ]));

        $tokens = $lexer->lex('{{    $foo                }}{{ klobna("sth")}}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::OUTPUT, '$foo', 1),
            new Token(Token::OUTPUT, 'klobna("sth")', 1),
        ]));
    }

    public function testLexingUnescaped()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('{!$foo!}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::UNESCAPED, '$foo', 1),
        ]));

        $tokens = $lexer->lex('{! $foo    !}{!SOMETHINGUNSAFE($foo) !}{! ok!}');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::UNESCAPED, '$foo', 1),
            new Token(Token::UNESCAPED, 'SOMETHINGUNSAFE($foo)', 1),
            new Token(Token::UNESCAPED, 'ok', 1),
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
            HTML, 1),
        ]));
    }

    public function testLexingComments()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex('foo{# klobna #}bar');
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TEXT, 'foo', 1),
            new Token(Token::TEXT, 'bar', 1),
        ]));
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
            new Token(Token::TEXT, '    <h1>', 1),
            new Token(Token::OUTPUT, '$foo', 1),
            new Token(Token::TEXT, '</h1>'.PHP_EOL.'    ', 1),
            new Token(Token::TAG, ['foreach', '$users as $user'], 2),
            new Token(Token::TEXT, PHP_EOL.'       <h2>', 3),
            new Token(Token::OUTPUT, '$user', 3),
            new Token(Token::TEXT, ' </h2>'.PHP_EOL.'        ', 3),
            new Token(Token::UNESCAPED, 'md($user->description)', 4),
            new Token(Token::TEXT, PHP_EOL.'    ', 5),
            new Token(Token::TAG_END, 'foreach', 5),
        ]));
    }

    public function testLexingWhitespaceStatements()
    {
        $lexer = $this->getLexer();
        $tokens = $lexer->lex(<<<'HTML'
            .foo \{
                color: red;
            }
        HTML);
    $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TEXT, <<<'HTML'
                .foo {
                    color: red;
                }
            HTML, 1)
        ]));
    }

    public function testLexingBlade()
    {
        $lexer = new Lexer(Syntax::blade());
        $tokens = $lexer->lex(<<<'HTML'
            <h1>{{ $foo }}</h1>
            foo@bar.baz
            @foreach($users as $user)
               <h2>{{ $user }} </h2>
                {!! md($user->description) !!}
            @endforeach
        HTML);
        $this->assertThat($tokens, $this->equalTo([
            new Token(Token::TEXT, '    <h1>', 1),
            new Token(Token::OUTPUT, '$foo', 1),
            new Token(Token::TEXT, "</h1>\n    foo@bar.baz\n    ", 1),
            new Token(Token::TAG, ['foreach', '$users as $user'], 3),
            new Token(Token::TEXT, "\n       <h2>", 4),
            new Token(Token::OUTPUT, '$user', 4),
            new Token(Token::TEXT, " </h2>\n        ", 4),
            new Token(Token::UNESCAPED, 'md($user->description)', 5),
            new Token(Token::TEXT, "\n    ", 6),
            new Token(Token::TAG_END, 'foreach', 6),
        ]));       
    }
}
