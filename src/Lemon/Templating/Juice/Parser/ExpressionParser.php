<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Contracts\Templating\Juice\Lexer;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Nodes\Expression\ArrayDefinition;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\Expression\FunctionCall;
use Lemon\Templating\Juice\Nodes\Expression\FunctionName;
use Lemon\Templating\Juice\Nodes\Expression\Indexing;
use Lemon\Templating\Juice\Nodes\Expression\Number;
use Lemon\Templating\Juice\Nodes\Expression\Variable;
use Lemon\Templating\Juice\Operators;
use Lemon\Templating\Juice\Token\PHPTokenKind;
use Lemon\Templating\Juice\Nodes\Expression\StringLiteral;

/**
 * Expression parser (the real challenge of this project)
 * 
 * Main concept is based around transfering grammar into recursive-descent parser
 * as see in https://en.wikipedia.org/wiki/Operator-precedence_parser
 * however, since we have lot of priorities, its better to have generic 
 * parser that works with priorities rather than looot of funtions
 *
 * todo [] array top
 * todo all the fancy dynamic stuff such as Parek::{$rizek} et al
 * todo duplicitni zavorky
 */
class ExpressionParser
{

    public function __construct(
        private Lexer $lexer,
        private Operators $ops,
    ) {

    }

    public function parse(): Expression 
    {
        return $this->parseExpression(Operators::HighestPriority);
    }

    public function parseExpression(int $priority): Expression
    {
        if ($priority === 0) {
            return $this->parsePrimary();
        }

        $position = $this->lexer->current()->position;

        $left = $this->parseExpression($priority - 1);
        $op = $this->lexer->peek();
        if ($op === null || ($this->ops->binary[$op->content][0] ?? null) !== $priority) {
            return $left;
        }
        $op = $this->lexer->next();
        $this->lexer->next();
        $right = $this->parseExpression($priority - 1);

        return new (
            $this->ops->binary[$op->content][1] ?? BinaryOperation::class
        )($left, $op->content, $right, $position); 
    }

    private function parsePrimary(): Expression
    {
        $position = $this->lexer->current()->position;
        return 
            $this->parseString()
            ?? $this->parseNumber()
            ?? $this->parseVariable()
            ?? $this->parseBrackets()
            ?? $this->parseFunctionName()
            ?? $this->parseArray()
            ?? throw new CompilerException("Unexpected token", $position->line, $position->pos) // TAK SES PICUS
        ;
    }

    private function parseString(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::StringDelim) {
            return null;
        }
        $position = $token->position;
        $end = $token->content;
        $this->lexer->changeContext(Context::JuiceString);
        $result = [];
        // todo variables and stuff we could just { and change context muhahahahaha
        while ($this->lexer->next()->kind !== PHPTokenKind::StringDelim
            || $this->lexer->current()?->content !== $end 
        ) {
            if ($this->lexer->current() === null) {
                throw new CompilerException('Unclosed string', $position->line, $position->pos);
            }
            $result[] = $this->lexer->current()->content;
        }
        return new StringLiteral($result, $position);    
    }

    private function parseNumber(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Number) {
            return null;
        }

        return new Number($token->content, $token->position);
    }

    private function parseVariable(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Variable) {
            return null;
        }

        $result = new Variable($token->content, $token->position);
        $target = $result;
        while (($target = $this->parseIndexing($result)) !== null) {
            $result = $target;
        }

        return 
            $this->parseFunctionCall($result) 
            ?? $result
        ;
    }

    private function parseIndexing(Expression $target): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::OpenningSquareBracket) {
            return null;
        }
        $expr = $this->parse();
        if ($this->lexer->next()->kind !== PHPTokenKind::ClosingSquareBracket) {
            throw new CompilerException('Unclosed bracket', $token->position->line, $token->position->pos);
        }

        return new Indexing($target, $expr, $token->position);
    }


    private function parseFunctionName(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Name) {
            return null;
        }            

        return
            $this->parseFunctionCall($target = new FunctionName($token->content, $token->position))
            ?? $target // yes we do support straight function names unlike php, we're raised on php broski
        ;
    }

    private function parseFunctionCall(Expression $target): ?Expression 
    {
        $token = $this->lexer->peek();
        if ($token->kind !== PHPTokenKind::OpenningBracket) {
            return null;
        }      

        $this->lexer->next(); 

        $args = [];
        while ($this->lexer->next()->kind !== PHPTokenKind::ClosingBracket) {
            $args[] = $this->parse();
            $next = $this->lexer->peek();
            if ($next->kind === PHPTokenKind::Comma) {
                $this->lexer->next();
                continue;
            }  
            
            if ($next->kind !== PHPTokenKind::ClosingBracket) {
                // we could actualy not require it hahah but wr not in haskell
                throw new CompilerException('Expected , between function arguments', $next->position->line, $next->position->pos);
            }
        }

        return new FunctionCall($target, $args, $token->position);
    }

    private function parseBrackets(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::OpenningBracket) {
            return null;
        }           
        $this->lexer->next();

        $expr = $this->parse(); 

        if ($this->lexer->next()->kind !== PHPTokenKind::ClosingBracket) {
            throw new CompilerException('Unclosed bracket', $token->position->line, $token->position->pos);
        }

        return $expr;
    }

    private function parseArray(): ?Expression 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::OpenningSquareBracket) {
            return null;
        }     

        $items = [];
        while ($this->lexer->next()->kind !== PHPTokenKind::ClosingSquareBracket) {
            $items[] = $this->parse();
            $next = $this->lexer->peek();
            if ($next->kind === PHPTokenKind::Comma) {
                $this->lexer->next();
                continue;
            }  
            
            if ($next->kind !== PHPTokenKind::ClosingSquareBracket) {
                // we could actualy not require it hahah but wr not in haskell
                throw new CompilerException('Expected , between items of array', $next->position->line, $next->position->pos);
            }
        }

        return new ArrayDefinition($items, $token->position);
    }

    //private function parseClassAccess(): ?Expression 
    //{

    //}
    
    //private function parseNewClass(): ?Expression 
    //{
    //    $token = $this->lexer->current();
    //    if ($token->kind !== PHPTokenKind::New) {
    //        return null;
    //    }    

    //    // todo support string expressions
    //    if ($this->lexer->next()->kind !== PHPTokenKind::Name) {
    //        throw new CompilerException('Unexpected token after "new," expected class name', $token->position->line, $token->position->pos);
    //    }


    //}

}
