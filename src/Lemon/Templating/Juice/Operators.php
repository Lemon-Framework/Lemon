<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Nodes\Expression\Variable;

class Operators
{

    public const HighestPriority = 5;

    /**
     * Creates new class for managing operators
     *
     * @param array<string, array<int>> $binary List of binary operators with their piroriries
     * @param array<string, array<mixed>> $unary List of unary operators
     */
    public function __construct(
        /**
         * Contains all available operators with their priorities where smallest number means highest priority
         * 
         * @todo test priorities, add tree nodes
         */
        private ?array $binary = null, 
        /** @todo add leftness/rightness */
        private ?array $unary = null, 
    ) {
        $this->binary ??= [
            'as' => [0, fn(Expression $l) => throw new CompilerException('Operator as is not available in this context', $l->position)],
            'in' => [0, 'in_array(#l, #r)'],
            'instanceof' => [0, '#l instanceof #r'],
            '||' => [0, '#l||#r'],
            '&&' => [1, '#l&&#r'],
            '===' => [2, '#l===#r'],
            '!==' => [2, '#l!==#r'],
            '==' => [2, '#l==#r'],
            '!=' => [2, '#l!=#r'],
            '~=' => [2, 'preg_match(#r,#l)===1'], // bro thinks he perl
            '>=' => [2, '#l>=#r'],
            '<=' => [2, '#l<=#r'],
            '|' => [2, '#r(#l)'],
            '>' => [2, '#l>#l'], 
            '<' => [2, '#l<#r'],
            '??' => [2, '#l??#r'],
            '.' => [3, '#l.#r'],
            '+' => [3, '#l+#r'],
            '-' => [3, '#l-#r'],
            '*' => [4, '#l*#r'],
            '/' => [4, '#l/#r'],
            '//' => [4, 'intdiv(#l,#r)'],
            '%' => [4, '#l%#r'],
            //'?->' => [1], so these are not operators??? 
            //'->' => [1],  so these are not operators???
            //'::' => [1],  so these are not operators???
            '=' => [0, function(Expression $l, Expression $r, SematicContext $context, Generators $generators) {
                if (!($l instanceof Variable)) {
                    // todo position
                    throw new CompilerException('Left side of the = operator must be l-value (variable)', $l->position);
                }

                return $l->generate($context, $generators).'='.$r->generate($context, $generators);
            }],
            // ??=
        ];

        $this->unary ??= [
            '!' => true,
            '-' => true,
            // TODO 'new' => true, 
            // TODO '...' => true,
            "++" => false,
            "--" => false,
            '@' => true,
        ];

    }

    public function binary(): array
    {
        return $this->binary;
    }

    public function unary(): array
    {
        return $this->unary;
    }


}
