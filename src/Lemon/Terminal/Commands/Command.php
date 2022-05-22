<?php

declare(strict_types=1);

namespace Lemon\Terminal\Commands;

use Lemon\Support\Types\Str;
use Lemon\Terminal\Exceptions\CommandException;

class Command
{
    public readonly array $arguments;

    public readonly string $name;

    public function __construct(
        public readonly string $signature,
        public readonly callable $action,
        public readonly string $description = ''
    ) {
        [$this->name, $this->arguments] = $this->resolveSignature();
    }

    private function resolveSignature(): array
    {
        $signature = Str::split($this->signature, ' '); 
        $name = $signature[0];
        $result = [];
        foreach ($signature['1..'] as $argument) {
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
