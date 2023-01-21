<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Validator Zest
 * Provides static layer over the Lemon Validator.
 *
 * @method static \Lemon\Validation\Rules rules()                               Returns all rules.
 * @method static bool                    validate(array $data, array $ruleset) Determins whenever given data meets given rules.
 * @method static array                   resolveRules(array|string $rules)     Converts rules into same array.
 * @method static string error() Returns validation error
 * @method static bool hasError() Returns whenever validator failed
 *
 * @see \Lemon\Validation\Validator
 */
class Validator extends Zest
{
    public static function unit(): string
    {
        return 'validation';
    }
}
