<?php

declare(strict_types=1);

namespace Lemon\Validation;

use Lemon\Validation\Exceptions\ValidatorException;

class Rules
{
    private array $rules = [];

    public function numeric(string $target)
    {
        return is_numeric($target);
    }

    public function notNumeric(string $target)
    {
        return !$this->numeric($target);
    }

    public function email(string $target)
    {
        return filter_var($target, FILTER_VALIDATE_EMAIL) === $target;
    }

    public function url(string $target)
    {
        return filter_var($target, FILTER_VALIDATE_URL) === $target;
    }

    public function color(string $target)
    {
        return 1 === preg_match('/^#([a-fA-F0-9]{3}){1,2}$/', $target);
    }

    public function max(string $target, mixed $max)
    {
        return strlen($target) <= $max;
    }

    public function min(string $target, mixed $min)
    {
        return strlen($target) >= $min;
    }

    public function regex(string $target, string $patern): bool
    {
        return 1 === preg_match("/^{$patern}$/", $target);
    }

    public function notRegex(string $target, string $patern): bool
    {
        return !$this->regex($target, $patern);
    }

    public function contains(string $target, string $patern): bool
    {
        return 1 === preg_match("/{$patern}/", $target);
    }

    public function doesntContain(string $target, string $patern): bool
    {
        return !$this->contains($target, $patern);
    }

    public function date(string $target): bool
    {
        return preg_match('/\d{4}-\d{2}-\d{2}/', $target) === 1 && strtotime($target) !== false;
    }

    public function datetime(string $target): bool
    {
        return preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $target) === 1 && strtotime($target) !== false;
    }

    public function boolean(string $target): bool
    {
        return $target !== '' && filter_var($target, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
    }

    public function rule(string $name, callable $action): static
    {
        $this->rules[$name] = $action;

        return $this;
    }

    /**
     * Determins whenever given target meets given rule.
     *
     * @throws ValidatorException
     */
    public function call(string $target, array $rule): bool
    {
        $args = [];
        if (count($rule) > 1) {
            $args = array_slice($rule, 1);
        }

        if (method_exists($this, $rule[0])) {
            return $this->{$rule[0]}($target, ...$args);
        }

        if (array_key_exists($rule[0], $this->rules)) {
            return $this->rules[$rule[0]]($target, ...$args);
        }

        throw new ValidatorException('Validator rule '.$rule[0].' does not exist');
    }
}
