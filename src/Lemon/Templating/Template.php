<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Templating\Exceptions\TemplateException;

/**
 * Represents compiled template.
 */
final class Template
{
    public function __construct(
        public readonly string $raw_path,
        public readonly string $compiled_path,
        private array $data
    ) {
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function with(...$data): static
    {
        $this->data = [...$this->data, ...$data];
        return $this;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function render(): string
    {
        ob_start();

        $data = $this->data;

        extract($data);

        try {
            require $this->compiled_path;
        } catch (\Throwable $e) {
            ob_get_clean();

            throw TemplateException::from($e, $this->raw_path);
        }

        return ob_get_clean();
    }
}
