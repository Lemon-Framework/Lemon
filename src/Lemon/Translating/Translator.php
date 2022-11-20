<?php

declare(strict_types=1);

namespace Lemon\Translating;

use Lemon\Contracts\Translating\Translator as TranslatorContract;
use Lemon\Contracts\Config\Config;
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
        return 
            $this->translations()[$key] 
            ?? throw new TranslatorException('Undefined translation text '.$this->locale.'.'.$key)
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
     * Returns translations of curent locale
     */
    public function translations(): array 
    {
        if (!isset($this->data[$this->locale])) {
            if (!file_exists($file = Filesystem::join($this->directory, $this->locale).'.php')) {
                $this->locale = $this->fallback;
                return $this->translations();
            }
            
            $this->data[$this->locale] = require $file;
        }


        return $this->data[$this->locale];
    }

    /**
     * {@inheritdoc}
     */
    public function locale(): string
    {
        return $this->locale;
    }

    /**
     * Returns whenever curent locale is given locale
     */
    public function is(string $locale): bool
    {
        return $this->locale === $locale;
    } 
}
