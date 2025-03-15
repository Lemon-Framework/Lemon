<?php

declare(strict_types=1);

namespace Lemon\Templating\Exceptions;

use Exception;
use Lemon\Templating\Juice\Position;

/**
 * Actualy its rather transpiler so it should be transpiler exception :nerd:
 */
class CompilerException extends Exception
{
    public function __construct(string $message, Position $position)
    {
        $this->message = $message;
//        $this->line = $line ?? $this->line;
//        $this->pos = $pos ?? $this->pos; todo
    }
}
