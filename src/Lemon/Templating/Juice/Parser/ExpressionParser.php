<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Contracts\Templating\Juice\Lexer;
use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Context;
use Lemon\Templating\Juice\Nodes\Expression\ArrayDefinition;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\Expression\FunctionCall;
use Lemon\Templating\Juice\Nodes\Expression\FunctionName;
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
 */
class ExpressionParser
{

    public function __construct(
        private Lexer $lexer,
        private Operators $ops,
    ) {

    }

    public function parseExpression(int $priority): ?Node
    {
        if ($priority === 0) {
            return $this->parsePrimary();
        }

        $position = $this->lexer->current()->position;

        $left = $this->parseExpression($priority - 1);
        $op = $this->lexer->peek();
        if ($this->ops->binary[$op][0] != $priority) {
            return $left;
        }
        $op = $this->lexer->next();
        $right = $this->parseExpression($priority - 1);

        return new BinaryOperation($left, $op, $right, $position); 
    }

    private function parsePrimary(): ?Node
    {
        $position = $this->lexer->current()->position;
        return 
            $this->parseString()
            ?? $this->parseNumber()
            ?? $this->parseVariable()
            ?? $this->parseBrackets()
            ?? $this->parseFunctionCall()
            ?? $this->parseArray()
            ?? throw new CompilerException("Unexpected token", $position->line, $position->pos) // TAK SES PICUS
        ;
    }

    private function parseString(): ?Node 
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

    private function parseNumber(): ?Node 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Number) {
            return null;
        }

        return new Number($token->content, $token->position);
    }

    private function parseVariable(): ?Node 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Variable) {
            return null;
        }

        // $this->parseIndexing()
        // or function calling
        

        return new Variable($token->content, $token->position);
        
    }

    //private function parseIndexing(): ?Node 
    //{

    //}

    private function parseFunctionCall(): ?Node 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::Name) {
            return null;
        }       

        if ($this->lexer->next()->kind !== PHPTokenKind::OpenningBracket) {
            return new FunctionName($token->content, $token->position);
        }

        $args = [];
        while ($this->lexer->next()->kind !== PHPTokenKind::ClosingBracket) {
            $args[] = $this->parseExpression(Operators::HighestPriority);
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

        return new FunctionCall($token->content, $args, $token->position);
    }

    private function parseBrackets(): ?Node 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::OpenningBracket) {
            return null;
        }           
        $this->lexer->next();

        $expr = $this->parseExpression(Operators::HighestPriority); 

        if ($this->lexer->next()->kind !== PHPTokenKind::ClosingBracket) {
            throw new CompilerException('Unclosed bracket', $token->position->line, $token->position->pos);
        }

        return $expr;
    }

    private function parseArray(): ?Node 
    {
        $token = $this->lexer->current();
        if ($token->kind !== PHPTokenKind::OpenningSquareBracket) {
            return null;
        }     

        $items = [];
        while ($this->lexer->next()->kind !== PHPTokenKind::ClosingSquareBracket) {
            $items[] = $this->parseExpression(Operators::HighestPriority);
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

    //private function parseClassAccess(): ?Node 
    //{

    //}
    
    //private function parseNewClass(): ?Node 
    //{

    //}

}
