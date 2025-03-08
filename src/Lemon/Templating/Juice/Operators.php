<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Templating\Juice\Nodes\Expression\Operators\Pipe;
use Lemon\Templating\Juice\Nodes\Expression\Operators\RegexMatch;

class Operators
{

    public const HighestPriority = 4;

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
        public readonly array $binary = [
            'as' => [4],
            'in' => [4, In::class],
            'instanceof' => [4],
            '===' => [3],
            '!==' => [3],
            '==' => [3],
            '!=' => [3],
            '~=' => [3, RegexMatch::class], // bro thinks he perl
            '>=' => [3],
            '<=' => [3],
            '|' => [3, Pipe::class],
            '>' => [3], 
            '<' => [3],
            '??' => [3],
            '.' => [2],
            '+' => [2],
            '-' => [2],
            '*' => [1],
            '/' => [1],
            //'?->' => [1], so these are not operators??? 
            //'->' => [1],  so these are not operators???
            //'::' => [1],  so these are not operators???
            '=' => [4],
            // ??=
        ],

        /** @todo add leftness/rightness */
        public readonly array $unary = [
            '!' => true,
            '-' => true,
            'new' => true,
            '...' => true,
            "++" => false,
            "--" => false,
            '@' => true,
        ],
    ) {

    }

    /**
     * Builds regular expression for lexing given operator list
     *
     * @param array<string, mixed> $operators List of operators in format of this class 
     * @return string regex capable of lexing given operators (without deliminers)
     */
    private function buildOperatorsRe(array $operators): string
    {
        $operators = array_map(fn($op) => preg_quote($op, '/'), array_keys($operators));
        return '('.implode('|', $operators).')';
    }

    public function buildBinaryRe(): string {
        return $this->buildOperatorsRe($this->binary);
    }
    
    public function buildUnaryRe(): string {
        return $this->buildOperatorsRe($this->unary);
    }
}
