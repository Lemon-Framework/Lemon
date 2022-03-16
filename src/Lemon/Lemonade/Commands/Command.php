<?php

declare(strict_types=1);

use Lemon\Kernel\Lemonade\Signature;

class Command
{
    public $signature;

    public $action;

    public $description;

    public function __construct(string $signature, $action, $description = '')
    {
        $this->signature = new Signature($signature);
        $this->action = $action;
        $this->description = $description;
    }

    /**
     * Validates command signature with given input.
     */
    public function validate(string $input): array|false
    {
        if ($arguments = $this->signature->matches($input)) {
            return $arguments;
        }

        return false;
    }

    /**
     * Runs command with given arguments.
     */
    public function run(array $arguments): void
    {
        call_user_func_array($this->action, $arguments);
    }

    public function info()
    {
        $name = $this->signature->name;
        $description = $this->description ? $this->description : '';

        return ['name' => $name, 'description' => $description];
    }
}
