<?php

declare(strict_types=1);

namespace Lemon\Translating;

use Lemon\Contracts\Translating\Translator as TranslatorContract;
use Lemon\Contracts\Config\Config;
use Lemon\Support\Filesystem;

class Translator implements TranslatorContract
{
    private array $data = [];

    private string $directory;

    public string $fallback;

    public function __construct(
        private Config $config
    ) {
        $this->directory = $config->file('directory');
        $this->fallback = Filesystem::join($this->directory, $config->file('localization'));
    }

    public function text(string $key, string $localization): string
    {
        $this->load($localization);
    }

    public function load(string $localization): self
    {
        $file = Filesystem::join($this->directory, $localization);
        if (!is_file($file)) {
            $file = $this->fallback();
        }

        return $this;
    }
}
