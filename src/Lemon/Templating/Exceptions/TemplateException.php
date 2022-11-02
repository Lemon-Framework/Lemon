<?php

declare(strict_types=1);

namespace Lemon\Templating\Exceptions;

class TemplateException extends \ErrorException
{
    public static function from(\Throwable $original, string $source): self
    {
        return new self($original->getMessage(), $original->getCode(), 1, $source, $original->getLine());
    }
}
