<?php

declare(strict_types=1);

namespace Lemon\Terminal\Commands;

use Lemon\Terminal\Exceptions\CommandException;

class Command
{
    public readonly array $parameters;

    public readonly string $name;

    public function __construct(
        public string $signature,
        public readonly \Closure $action, // Maybe bad idea?
        public readonly string $description = ''
    ) {
        // maybe bad idea
        // TODO
        [$this->name, $this->parameters] = $this->resolveSignature($signature);
    }

    private function resolveSignature(string $signature): array
    {
        $signature = explode(' ', $signature);
        $name = $signature[0];
        $result = [];
        foreach (array_slice($signature, 1) as $argument) {
            if (preg_match('/^{([a-zA-Z0-9]+)}$/', $argument, $matches)) {
                $result[] = ['obligated', $matches[1]];
            } elseif (preg_match('/^{([a-zA-Z0-9]+)\?}$/', $argument, $matches)) {
                $result[] = ['optional', $matches[1]];
            } else {
                throw new CommandException('Signature of command '.$name.' contains invalid argument patern: '.$argument);
            }
        }

        return [$name, $result];
    }
}
