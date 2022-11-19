<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Translator Zest
 * Provides static layer over the Lemon Translator.
 *
 * @see \Lemon\Translating\Translator
 */
class Translator extends Zest
{
    public static function unit(): string
    {
        return 'translator';
    }
}
