<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Lexer;

use Lemon\Templating\Juice\Lexer\PHPLexer;
use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;
use Lemon\Tests\Testing\TestCase;

class PHPLexerTest extends TestCase
{
    public function testLexingString()
    {
        $lexer = new PHPLexer('"foo"');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::String, 1, 0, 'foo')
        ));
        $this->assertNull($lexer->lexNext());

        $lexer = new PHPLexer("'new'");
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::String, 1, 0, 'new')
        ));
    }

    public function testLexingInt()
    {
        $lexer = new PHPLexer('37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '37')
        ));
        $this->assertNull($lexer->lexNext());

        $lexer = new PHPLexer('-37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '-37')
        ));

        $lexer = new PHPLexer('3.7');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '3.7')
        ));

        $lexer = new PHPLexer('.37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '.37')
        ));
    }

    public function testLexingVariables()
    {
        $lexer = new PHPLexer('$F_oo');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Variable, 1, 0, 'F_oo')
        ));
        $this->assertNull($lexer->lexNext());
    }

    public function testLexingName()
    {
        $lexer = new PHPLexer('foo\Bar\b_az');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Name, 1, 0, 'foo\Bar\b_az')
        ));
        $this->assertNull($lexer->lexNext());
    }

    public function testLexingOperators()
    {
        $lexer = new PHPLexer('+');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::BinaryOperator, 1, 0, '+')
        ));
        $this->assertNull($lexer->lexNext());

        $lexer = new PHPLexer('+ - * / % = += -= /= *= %= <= >= <=> < > ?? .= . || &&');
        $this->assertThat($lexer->lex(), $this->equalTo([
            new Token(TokenKind::BinaryOperator, 1, 0, '+'),
            new Token(TokenKind::BinaryOperator, 1, 2, '-'),
            new Token(TokenKind::BinaryOperator, 1, 4, '*'),
            new Token(TokenKind::BinaryOperator, 1, 6, '/'),
            new Token(TokenKind::BinaryOperator, 1, 8, '%'),
            new Token(TokenKind::BinaryOperator, 1, 10, '='),
            new Token(TokenKind::BinaryOperator, 1, 12, '+='),
            new Token(TokenKind::BinaryOperator, 1, 15, '-='),
            new Token(TokenKind::BinaryOperator, 1, 18, '/='),
            new Token(TokenKind::BinaryOperator, 1, 21, '*='),
            new Token(TokenKind::BinaryOperator, 1, 24, '%='),
            new Token(TokenKind::BinaryOperator, 1, 27, '<='),
            new Token(TokenKind::BinaryOperator, 1, 30, '>='),
            new Token(TokenKind::BinaryOperator, 1, 33, '<=>'),
            new Token(TokenKind::BinaryOperator, 1, 37, '<'),
            new Token(TokenKind::BinaryOperator, 1, 39, '>'),
            new Token(TokenKind::BinaryOperator, 1, 41, '??'),
            new Token(TokenKind::BinaryOperator, 1, 44, '.='),
            new Token(TokenKind::BinaryOperator, 1, 46, '.'),
            new Token(TokenKind::BinaryOperator, 1, 48, '||'),
            new Token(TokenKind::BinaryOperator, 1, 51, '&&'),
        ]));   

        $lexer = new PHPLexer('1 + $foo');
        $this->assertThat($lexer->lex(), $this->equalTo([
            new Token(TokenKind::Number, 1, 0, '1'),
            new Token(TokenKind::BinaryOperator, 1, 2, '+'),
            new Token(TokenKind::Variable, 1, 4, 'foo')
        ]));
    } 
}
