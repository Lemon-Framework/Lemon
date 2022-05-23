<?php

declare(strict_types=1);

namespace Lemon\Terminal\Commands;

class Dispatcher
{
    /** @var array<Command> */
    private array $commands = [];

    public function add(Command $command): static
    {
        $this->commands[$command->name] = $command;

        return $this;
    }

    public function dispatch(array $arguments): array|string
    {
        $name = $arguments[0];
        $arguments = array_slice($arguments, 1);

        if (!isset($this->commands[$name])) {
            return 'Command '.$name.' was not found.'; // Todo hints 1000iq
        }

        $command = $this->commands[$name];

        $arguments = $this->parseArguments($arguments);

        $result = [];
        // Todo types
        foreach ($command->arguments as $argument) {
            if (isset($arguments[$argument[1]])) {
                $result[$argument] = $arguments[$argument];
            } elseif ('optional' != $argument[0]) {
                return 'Argument '.$argument[1].' is missing.';
            }
        }

        return [$command->action, $result];
    }

    private function parseArguments(array $arguments): array
    {
        $result = [];
        foreach ($arguments as $argument) {
            [$name, $value] = explode('=', $argument);
            $result[$name] = $value;
        }

        return $result;
    }
}
