<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Contracts\Templating\Juice\Lexer;
use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Operators;

/**
 * Expression parser (the real challenge of this project)
 * 
 * Main concept is based around transfering grammar into recursive-descent parser
 * as see in https://en.wikipedia.org/wiki/Operator-precedence_parser
 * however, since we have lot of priorities, its better to have generic 
 * parser that works with priorities rather than looot of funtions
 */
class ExpressionParser
{

    public function __construct(
        private Lexer $lexer,
        private Operators $ops,
    ) {

    }

    public function parse(int $priority): ?Node
    {
        if ($priority === 0) {
            return $this->parsePrimary();
        }

        $left = $this->parse($priority - 1);
        $op = $this->lexer->peek();
        if ($this->ops->binary[$op][0] != $priority) {
            return $left;
        }
        $op = $this->lexer->next();
        $right = $this->parse($priority - 1);

        return new BinaryOperation($left, $op, $right, $position); 
    }

    private function parsePrimary(): ?Node
    {
        $token = $this->lexer->next();

        return match ($token->kind) {
        
            default => null,
        };
    }

}
