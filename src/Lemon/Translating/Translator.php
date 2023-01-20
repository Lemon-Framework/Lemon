<?php

declare(strict_types=1);

namespace Lemon\Translating;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Translating\Translator as TranslatorContract;
use Lemon\Support\Filesystem;
use Lemon\Translating\Exceptions\TranslatorException;

class Translator implements TranslatorContract
{
    private string $locale;

    private array $data = [];

    private string $directory;

    private string $fallback;

    public function __construct(
        Config $config
    ) {
        $this->directory = $config->file('translating.directory');
        $this->fallback = $config->get('translating.fallback');
        $this->locale = $this->fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function text(string $key): string
    {
        if (!strpos($key, '.')) {
            throw new TranslatorException('Translation keys must be in format file.key');
        }

        [$file, $key] = explode('.', $key);
        return
            $this->translations($file)[$key]
            ?? throw new TranslatorException('Undefined translation key '.$this->locale.'.'.$file.'.'.$key);
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function locate(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Returns translations of curent locale.
     */
    public function translations(string $filename): array
    {
        if (!isset($this->data[$this->locale][$filename])) {
            if (!file_exists($file = Filesystem::join($this->directory, $this->locale, $filename).'.php')) {
                if ($this->locale === $this->fallback) {
                    throw new TranslatorException('Undefined translation file '.$this->locale.'.'.$filename);
                }
                $this->locale = $this->fallback;

                return $this->translations($filename);
            }

            $this->data[$this->locale][$filename] = require $file;
        }

        return $this->data[$this->locale][$filename];
    }

    /**
     * {@inheritdoc}
     */
    public function locale(): string
    {
        return $this->locale;
    }

    /**
     * Returns whenever curent locale is given locale.
     */
    public function is(string $locale): bool
    {
        return $this->locale === $locale;
    }
}
