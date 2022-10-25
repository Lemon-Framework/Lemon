<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Lexer;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\{Token, TokenKind};

class PHPLexer
{
    public const Tokens = [
        '...' => TokenKind::Elipsis,
        '+=' => TokenKind::BinaryOperator,
        '-=' => TokenKind::BinaryOperator,
        '/=' => TokenKind::BinaryOperator,
        '*=' => TokenKind::BinaryOperator,
        '.=' => TokenKind::BinaryOperator,
        '%=' => TokenKind::BinaryOperator,
        '++' => TokenKind::UnaryOperator,
        '->' => TokenKind::Arrow,
        '--' => TokenKind::UnaryOperator,
        '**' => TokenKind::BinaryOperator,
        '+' => TokenKind::BinaryOperator,
        '-' => TokenKind::BinaryOperator,
        '*' => TokenKind::BinaryOperator,
        '/' => TokenKind::BinaryOperator,
        '%' => TokenKind::BinaryOperator,
        '.' => TokenKind::BinaryOperator,
        '===' => TokenKind::BinaryOperator,
        '==' => TokenKind::BinaryOperator,
        '=>' => TokenKind::BinaryOperator,
        '=' => TokenKind::BinaryOperator,
        '<=>' => TokenKind::BinaryOperator,
        '<=' => TokenKind::BinaryOperator,
        '>=' => TokenKind::BinaryOperator,
        '<' => TokenKind::BinaryOperator,
        '>' => TokenKind::BinaryOperator,
        '!==' => TokenKind::BinaryOperator,
        '!=' => TokenKind::BinaryOperator,
        '|>' => TokenKind::Pipe,
        '?->' => TokenKind::NullArrow,
        '??' => TokenKind::BinaryOperator,
        '?' => TokenKind::QuestionMark,
        '::' => TokenKind::DoubleColon,
        ':' => TokenKind::Colon,
        '[' => TokenKind::OpeningSquareBracket,
        ']' => TokenKind::ClosingSquareBracket,
        '(' => TokenKind::OpeningBracket,
        ')' => TokenKind::ClosingBracket,
        '!' => TokenKind::Not,
        'as ' => TokenKind::As,
        'in ' => TokenKind::In,
        '||' => TokenKind::BinaryOperator,
        '&&' => TokenKind::BinaryOperator,
        'instanceof ' => TokenKind::Instanceof,
        'new ' => TokenKind::New, 
        ',' => TokenKind::Comma,
        'fn' => TokenKind::Fn,
    ];

    private int $pos = 0;
    private int $line = 1;

    public function __construct(
        private string $code
    ) {

    }

    public function lexNext(): Token
    {
        $this->trim();
        return
            $this->lexString()
            ?? $this->lexNumber()
            ?? $this->lexVariable()
            ?? $this->lexKeyWords()
            ?? $this->lexName()
            ?? throw new CompilerException('Unexpected character', $this->line);
        ;
    }

    public function lexKeyWords(): Token|null
    {
        foreach (self::Tokens as $token => $kind) {
            if (str_starts_with($this->code, $token)) {
                $len = strlen($token);
                $this->code = substr($this->code, $len);
                return new Token($kind, $this->line, $this->pos, substr($this->code, 0, $len));
            }
        }

        return null;
    }

    public function lexString(): Token|null
    {
        if (!$this->isQuote($this->code[0])) {
            return null;
        }

        // todo interpolation (why is php so good language)
        $result = '';
        for ($index = 1; 
            isset($this->code[$index]) 
            && !$this->isQuote($this->code[$index]);
        $index++) {
            $result .= $this->code[$index];
        }

        if (!isset($this->code[$index])) {
            throw new CompilerException('Unexpected end of string', $this->line);
        }

        $this->code = substr($this->code, 0, $index + 1);

        $this->pos += $index + 1;

        return new Token(TokenKind::String, $this->pos, $this->line, $result);
    }

    public function lexNumber(): Token|null
    {
        // TODO
        // -1.2
        return null;
    }

    public function lexVariable(): Token|null
    {
        if ($this->code[0] !== '$') {
            return null;
        }

        if (strlen($this->code) === 1) {
            return null;
        }

        $result = '';
        for ($index = 1; $this->isNameChar($this->code[$index]); $index++) {
            $result .= $this->code[$index];
        }

        $this->code = substr($this->code, 0, $index);

        return new Token(TokenKind::Variable, $this->pos, $this->line, $result);
    } 

    public function lexName(): Token|null
    {
        $result = '';
        for ($index = 1;
            isset($this->code[$index])
            && ($this->isNameChar($c = $this->code[$index])
                || $c === '\\'
            ); 
        $index++) {
            $result .= $c;
        }

        $this->code = substr($this->code, 0, $index);

        return new Token(TokenKind::Name, $this->pos, $this->line, $result);
    }

    private function trim(): void
    {
        for ($index = 0; ctype_space($this->code[$index]); $index++) {
            if ($this->code[$index] === "\n") {
                $this->line++;
                $this->pos = 0;
            } else {
                $this->pos++;
            }
        }
    }

    private function isQuote(string $char): bool
    {
        return 
            $char === '"' 
            || $char === "'"
        ;
    }

    private function isNameChar(string $char): bool
    {
        return 
            ctype_alnum($char)
            || $char === '_'
        ;
    }
}
