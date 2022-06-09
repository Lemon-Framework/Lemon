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
        public readonly string $raw_path,
        public readonly string $compiled_path,
        private array $data
    ) {
    }

    public function __toString(): string
    {
        ob_start();
        $this->render();

        return ob_get_clean();
    }

    /**
     * Renders template.
     */
    public function render(): void
    {
        extract($this->data);

        try {
            require $this->compiled_path;
        } catch (Throwable $e) {
            throw TemplateException::from($e, $this->raw_path);
        }
    }
}
