<?php

declare(strict_types=1);

namespace Lemon\Contracts\Validation;

interface Validator
{
    /**
     * Returns all rules.
     */
    public function rules(): Rules;

    /**
     * Determins whenever given data meets given rules.
     */
    public function validate(array $data, array $ruleset): bool;
}
