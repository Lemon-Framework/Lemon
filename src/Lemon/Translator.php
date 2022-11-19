<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Translator Zest
 * Provides static layer over the Lemon Translator.
 *
 * @method static string text(string $key) 
 * @method static \Lemon\Translating\Translator locate(string $locale)
 * @method static array translations() 
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
