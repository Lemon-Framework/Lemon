<?php

declare(strict_types=1);

namespace Lemon\Templating\Exceptions;

use Lemon\Debug\Handling\Attributes\Doc;

#[Doc('getting_started/templates')]
class CompilerException extends \Exception
{
    public function __construct(string $message, int $line = null)
    {
        $this->message = $message;
        $this->line = $line ?? $this->line;
    }
}
