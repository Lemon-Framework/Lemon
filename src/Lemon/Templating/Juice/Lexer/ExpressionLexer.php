<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Lexer;

use Generator;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\{Token, TokenKind};

class ExpressionLexer
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
        '?:' => TokenKind::BinaryOperator,
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

    public function lexNext(): Token|null
    {
        if ($this->code === '') {
            return null;
        }

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

    /**
     * @return Generator<Token>
     */
    public function lex(): Generator
    {
        while ($next = $this->lexNext()) {
            yield $next;
        }
    }

    public function lexKeyWords(): Token|null
    {
        foreach (self::Tokens as $token => $kind) {
            if (str_starts_with($this->code, $token)) {
                $len = strlen($token);
                $this->code = substr($this->code, $len);
                $token = new Token($kind, $this->line, $this->pos, trim($token)); 
                $this->pos += $len;
                return $token;
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

        $token = new Token(TokenKind::String, $this->line, $this->pos, $result);


        $this->code = substr($this->code, $index + 1);
        $this->pos += $index + 1;

        return $token;
    }

    public function lexNumber(): Token|null
    {
        $result = '';
        for ($index = 0; 
            isset($this->code[$index])
            && (ctype_digit($c = $this->code[$index])
                || in_array($c, ['-', '.']
            ));
        $index++) {
            $result .= $c;
        }
        
        if (!is_numeric($result)) {
            return null;
        }

        $this->code = substr($this->code, $index);

        $token = new Token(TokenKind::Number, $this->line, $this->pos, $result);
        $this->pos += $index;

        return $token;
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
        for ($index = 1; 
            isset($this->code[$index])
            && $this->isNameChar($this->code[$index]); 
        $index++) {
            $result .= $this->code[$index];
        }

        $this->code = substr($this->code, $index);

        $token = new Token(TokenKind::Variable, $this->line, $this->pos, $result);

        $this->pos += $index;

        return $token;
    } 

    public function lexName(): Token|null
    {
        $result = '';
        for ($index = 0;
            isset($this->code[$index])
            && ($this->isNameChar($c = $this->code[$index])
                || $c === '\\'
            ); 
        $index++) {
            $result .= $c;    
        }

        if ($result === '') {
            return null;
        }

        $this->code = substr($this->code, $index);

        return new Token(TokenKind::Name, $this->line, $this->pos, $result);
    }

    private function trim(): void
    {
        for ($index = 0; ctype_space($this->code[$index]); $index++) {
            if ($this->code[$index] === "\n") {
                $this->line++;
                $this->pos = -1;
            } else {
                $this->pos++;
            }
        } 

        $this->code = substr($this->code, $index);
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
