<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Kernel\Lifecycle;

class TemplateFactory
{
    public function __construct(
        private Compiler $compiler,
        private Lifecycle $lifecycle
    )
    {
        
    }


    public function make(string $file, array $data): Template
    {
        return new Template(); // TODO
    }
}
