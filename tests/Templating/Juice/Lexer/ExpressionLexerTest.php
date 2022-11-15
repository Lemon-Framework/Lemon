<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Lexer;

use Lemon\Contracts\Templating\Compiler;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Lexer\ExpressionLexer;
use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;
use Lemon\Tests\Testing\TestCase;

class ExpressionLexerTest extends TestCase
{
    public function testLexingString()
    {
        $lexer = new ExpressionLexer('"foo"');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::String, 1, 0, 'foo')
        ));
        $this->assertNull($lexer->lexNext());

        $lexer = new ExpressionLexer("'new'");
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::String, 1, 0, 'new')
        ));
    }

    public function testLexingInt()
    {
        $lexer = new ExpressionLexer('37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '37')
        ));
        $this->assertNull($lexer->lexNext());

        $lexer = new ExpressionLexer('-37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '-37')
        ));

        $lexer = new ExpressionLexer('3.7');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '3.7')
        ));

        $lexer = new ExpressionLexer('.37');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Number, 1, 0, '.37')
        ));
    }

    public function testLexingVariables()
    {
        $lexer = new ExpressionLexer('$F_oo');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Variable, 1, 0, 'F_oo')
        ));
        $this->assertNull($lexer->lexNext());
    }

    public function testLexingName()
    {
        $lexer = new ExpressionLexer('foo\Bar\b_az');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::Name, 1, 0, 'foo\Bar\b_az')
        ));
        $this->assertNull($lexer->lexNext());
    }

    public function testLexingOperators()
    {
        $lexer = new ExpressionLexer('+');
        $this->assertThat($lexer->lexNext(), $this->equalTo(
            new Token(TokenKind::BinaryOperator, 1, 0, '+')
        ));
        $this->assertNull($lexer->lexNext());

<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('+ - * / % = += -= /= *= %= <= >= <=> < > ?? .= . || &&    ?:');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('+ - * / % = += -= /= *= %= <= >= <=> < > ?? .= . || &&    ?:');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
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
            new Token(TokenKind::BinaryOperator, 1, 47, '.'),
            new Token(TokenKind::BinaryOperator, 1, 49, '||'),
            new Token(TokenKind::BinaryOperator, 1, 52, '&&'),
            new Token(TokenKind::BinaryOperator, 1, 58, '?:'),
        ]));   

<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('1 + $foo');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('1 + $foo');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
            new Token(TokenKind::Number, 1, 0, '1'),
            new Token(TokenKind::BinaryOperator, 1, 2, '+'),
            new Token(TokenKind::Variable, 1, 4, 'foo')
        ]));

<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('$foo + 1');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('$foo + 1');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
            new Token(TokenKind::Variable, 1, 0, 'foo'),
            new Token(TokenKind::BinaryOperator, 1, 5, '+'),
            new Token(TokenKind::Number, 1, 7, '1')
        ]));

<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('"parek"."v rohliku"');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('"parek"."v rohliku"');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
            new Token(TokenKind::String, 1, 0, 'parek'),
            new Token(TokenKind::BinaryOperator, 1, 7, '.'),
            new Token(TokenKind::String, 1, 8, 'v rohliku'),
        ]));
    } 

    public function testLexingNew()
    {
<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('new Foo\Bar');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('new Foo\Bar');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
            new Token(TokenKind::New, 1, 0, 'new'),
            new Token(TokenKind::Name, 1, 4, 'Foo\Bar'),
        ]));       

<<<<<<< HEAD:tests/Templating/Juice/Lexer/ExpressionLexerTest.php
        $lexer = new ExpressionLexer('new       Foo\Bar');
        $this->assertThat($lexer->lex(), $this->equalTo([
=======
        $lexer = new PHPLexer('new       Foo\Bar');
        $this->assertThat(iterator_to_array($lexer->lex()), $this->equalTo([
>>>>>>> d983816e95130bc9a5639100aa61594a08580e6b:tests/Templating/Juice/Lexer/PHPLexerTest.php
            new Token(TokenKind::New, 1, 0, 'new'),
            new Token(TokenKind::Name, 1, 10, 'Foo\Bar'),
        ]));
    }
}
