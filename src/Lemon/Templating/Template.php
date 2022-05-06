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
        private string $raw_path,
        private string $compiled_path,
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
            require $this->compiled_path;
        } catch (Throwable $e) {
            throw new TemplateException($e, $this->raw_path);
        }
    }
}
