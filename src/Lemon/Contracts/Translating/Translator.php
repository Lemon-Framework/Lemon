<?php

declare(strict_types=1);

namespace Lemon\Contracts\Translating;

interface Translator
{
    public function text(string $key, string $localization): string;

}
