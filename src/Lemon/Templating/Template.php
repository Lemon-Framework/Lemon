<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Templating\Exceptions\TemplateException;
use Throwable;

/**
 * Represents compiled template.
 */
class Template
{
    public function __construct(
        private string $source,
        private string $compiled,
        private array $data
    ) {
    }

    /**
     * Renders template.
     */
    public function render(): void
    {
        compact($this->data);

        try {
            require $this->compiled;
        } catch (Throwable $e) {
            throw new TemplateException($e, $this->source);
        }
    }
}
