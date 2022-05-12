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

    public function rules(): Rules
    {
        return $this->rules;
    }

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

                if (!$this->rules->call($key, $rule)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function resolveRules(string|array $rules): array
    {
        // TODO better parser
        if (is_array($rules)) {
            return $rules;
        }

        return array_map(
            fn ($item) => explode(':', $item),
            explode('|', $rules)
        );
    }
}
