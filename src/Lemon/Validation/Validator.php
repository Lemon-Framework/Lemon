<?php

declare(strict_types=1);

namespace Lemon\Validation;

use Lemon\Contracts\Translating\Translator;
use Lemon\Contracts\Validation\Validator as ValidatorContract;

class Validator implements ValidatorContract
{
    private Rules $rules;

    private array $error = [];

    public function __construct(
        private Translator $translator
    ) {
        $this->rules = new Rules();
    }

    /**
     * Returns all rules.
     */
    public function rules(): Rules
    {
        return $this->rules;
    }

    /**
     * Adds Validation error.
     */
    public function addError(string $key, string $field, string $arg): static
    {
        $this->error = [$key, $field, $arg];
        return $this;
    }

    /**
     * Returns validation error.
     */
    public function error(): string
    {
        [$key, $field, $arg] = $this->error;

        return str_replace(['%field', '%arg'], [$field, $arg], $this->translator->text('validation.'.$key));
    }

    /**
     * Returns whenever validator failed.
     */
    public function hasError(): bool
    {
        return [] !== $this->error;
    }

    /**
     * Determins whenever given data meets given rules.
     */
    public function validate(array $data, array $ruleset): bool
    {
        foreach ($ruleset as $key => $rules) {
            $rules = $this->resolveRules($rules);
            if (!array_key_exists($key, $data) || 0 === strlen((string) $data[$key])) {
                if (in_array(['optional'], $rules)) {
                    continue;
                }

                $this->addError('missing', $key, '');

                return false;
            }
            foreach ($rules as $rule) {
                if ('optional' == $rule[0]) {
                    continue;
                }

                if (!$this->rules->call((string) $data[$key], $rule)) {
                    $this->addError($rule[0], $key, $rule[1] ?? '');

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Converts rules into same array.
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
