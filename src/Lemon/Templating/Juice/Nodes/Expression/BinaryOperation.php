<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class BinaryOperation implements Expression
{
    public function __construct(
        public readonly Expression $left,
        public readonly String $op, 
        public readonly Expression $right,
        public readonly Position $position,
    ) {

    }

     
    public function generate(SematicContext $context, Generators $generators): string 
    {
        $generator = $generators->operators->binary()[$this->op][1];
        if (is_callable($generator)) {
            return $generator($this->left, $this->right, $context, $generators);
        }

        if (!is_string($generator)) {
            throw new CompilerException("Generator for operator {$this->op} is not valid");
        }

        return str_replace(
            ['#l', '#r'],
            [$this->left->generate($context, $generators), $this->right->generate($context, $generators)],
            $generator,
        );
    }
}
