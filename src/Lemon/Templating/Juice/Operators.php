<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

class Operators
{
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
        public readonly array $unary = [
            '!' => [],
            '-' => [],
            'new' => [],
            '...' => [],
        ],
    ) {

    }

    public function buildBinaryRe(): string
    {
        return '('.implode('|', array_keys($this->binary)).')';
    }
    public function buildUnaryRe(): string
    {
        return '('.implode('|', array_keys($this->unary)).')';
    }
}
