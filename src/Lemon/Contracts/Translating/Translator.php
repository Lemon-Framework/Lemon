<?php

declare(strict_types=1);

namespace Lemon\Contracts\Translating;

interface Translator
{
    public function text(string $key): string;

    public function locate(string $locale): self;
}
