<?php

declare(strict_types=1);

namespace Lemon\Templating\Exceptions;

use Exception;
use Throwable;

class TemplateException extends Exception
{
    public function __construct(Throwable $original, string $source) 
    {
        parent::__construct($original->getMessage(), $original->getCode(), $original->getPrevious());
        $this->file = $source;
    }
}
