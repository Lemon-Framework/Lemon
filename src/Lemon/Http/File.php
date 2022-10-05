<?php

declare(strict_types=1);

namespace Lemon\Http;

class File
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $tmp_path,
        public readonly int $error,
        public readonly int $size
    ) {
    }

    public function read(): string
    {
        return file_get_contents($this->tmp_path);
    }

    public function copy(string $new): static
    {
        copy($this->tmp_path, $new);

        return $this;
    }
}
