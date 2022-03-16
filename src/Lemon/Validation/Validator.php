<?php

declare(strict_types=1);

namespace Lemon\Validation;

class Validator
{
    public function isNumeric($value)
    {
        return is_numeric($value);
    }

    public function isEmail(string $target)
    {
        return filter_var($target, FILTER_VALIDATE_EMAIL) === $target;
    }

    public function isUrl(string $target)
    {
        return filter_var($target, FILTER_VALIDATE_URL) === $target;
    }
}
