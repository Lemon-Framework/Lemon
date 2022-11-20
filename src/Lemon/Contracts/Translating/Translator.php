<?php

declare(strict_types=1);

namespace Lemon\Contracts\Translating;

interface Translator
{
    /**
     * Returns text of given key
     */
    public function text(string $key): string;

    /**
     * Sets locale (language) to curent user
     */
    public function locate(string $locale): self;

    /**
     * Returns curen locale (language)
     */
    public function locale(): string;
}
