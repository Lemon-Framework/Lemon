<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

class Operators
{
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
            '=' => [4],
            'as' => [4],
            'in' => [4],
            'instanceof' => [4],
            '==' => [3],
            '!=' => [3],
            '===' => [3],
            '!==' => [3],
            '>' => [3], 
            '<' => [3],
            '>=' => [3],
            '<=' => [3],
            '|>' => [3],
            '.' => [2],
            '+' => [2],
            '-' => [2],
            '*' => [1],
            '/' => [1],
            '->' => [1],
            '?->' => [1],
            '::' => [1],
        ],

        /** @todo add leftness/rightness */
        public readonly array $unary = [
            '!' => [],
            '-' => [],
            'new' => [],
            '...' => [],
            "++" => [],
            "--" => [],
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
        $operators = array_map("preg_quote", array_keys($operators));
        return '('.implode('|', $operators).')';
    }

    public function buildBinaryRe(): string {
        return $this->buildOperatorsRe($this->binary);
    }
    
    public function buildUnaryRe(): string {
        return $this->buildOperatorsRe($this->unary);
    }
}
