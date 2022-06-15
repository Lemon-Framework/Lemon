<?php

declare(strict_types=1);

namespace Lemon\Validation;

use Lemon\Support\Types\Arr;

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
        return preg_match('/^#([a-fA-F0-9]{3}){1,2}$/', $target) === 1;
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
        return preg_match("/^$patern$/", $target) === 1;
    }

    public function notRegex(string $target, string $patern): bool
    {
        return !$this->regex($target, $patern);
    }
 
    public function contains(string $target, string $patern): bool
    {
        return preg_match("/{$patern}/", $target) === 1;
    }

    public function doesntContain(string $target, string $patern): bool
    {
        return !$this->contains($target, $patern);
    }

    public function rule(string $name, callable $action): static
    {
        $this->rules[$name] = $action;

        return $this;
    }

    public function call(string $target, array $rule): bool
    {
        $args = [];
        if (count($rule) > 1) {
            $args = array_slice($rule, 1);
        }

        if (method_exists($this, $rule[0])) {
            return $this->{$rule[0]}($target, ...$args);
        }

        if (Arr::hasKey($this->rules, $rule[0])) {
            return $this->rules[$rule[0]]($target, ...$args);
        }

        throw new ValidatorException('Validator rule '.$rule[0].' does not exist');
    }
}
