<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Translator Zest
 * Provides static layer over the Lemon Translator.
 *
 * @method static string text(string $key)                                                    Returns text of given key
 * @method static \Lemon\Translating\Translator locate(string $locale) Sets locale (language) to curent user
 * @method static array translations()                                                        Returns translations of curent locale
 * @method static string locale() Returns curent locale (language)
 * @method static bool is(string $key)                                                        Returns whenever curent locale is given locale
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
