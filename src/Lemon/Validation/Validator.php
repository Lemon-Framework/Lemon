<?php

declare(strict_types=1);

namespace Lemon\Validation;

use Lemon\Support\Types\Arr;

class Validator
{
    private Rules $rules;

    public function __construct()
    {
        $this->rules = new Rules();
    }

    /**
     * Returns all rules
     */
    public function rules(): Rules
    {
        return $this->rules;
    }

    /**
     * Determins whenever given data meets given rules
     */
    public function validate(array $data, array $ruleset): bool
    {
        foreach ($ruleset as $key => $rules) {
            $rules = $this->resolveRules($rules);
            if (!Arr::hasKey($data, $key)) {
                if (Arr::has($rules, ['optional'])) {
                    continue;
                }

                return false;
            }
            foreach ($rules as $rule) {
                if ('optional' == $rule[0]) {
                    continue;
                }

                if (!$this->rules->call($data[$key], $rule)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Converts rules into same array
     */
    public function resolveRules(string|array $rules): array
    {
        if (is_array($rules)) {
            return $rules;
        }

        // TODO regex or parser
        return array_map(
            fn ($item) => explode(':', $item),
            explode('|', $rules)
        );
    }
}
