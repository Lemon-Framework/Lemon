<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Exceptions;

use Exception;

class CompilerException extends Exception
{
    public function __construct(string $message, int $line = null)
    {
        $this->message = $message;
        $this->line = $line ?? $this->line;
    }
}
