<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

use Stringable;

class String_ implements Stringable
{
    /** String value */
    public string $value;

    /**
     * Lemon string type.
     */
    public function __construct(string $subject)
    {
        $this->value = $subject;
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * Returns size of string.
     */
    public function size(): int
    {
        return strlen($this->value);
    }

    /**
     * Returns size of string.
     */
    public function len(): int
    {
        return strlen($this->value);
    }

    /**
     * Splits string to array by separator.
     *
     * @phpstan-param non-empty-string $separator
     */
    public function split(string $separator): Array_
    {
        return new Array_(
            explode($separator, $this->value)
        );
    }

    /**
     * Joins given Array items with string.
     */
    public function join(array $array): self
    {
        return String_::from(
            implode($this->value, $array)
        );
    }

    /**
     * Converts first character to uppercase.
     */
    public function capitalize(): self
    {
        $this->value = ucfirst($this->value);

        return $this;
    }

    /**
     * Converts first character to lovercase.
     */
    public function decapitalize(): self
    {
        $this->value = lcfirst($this->value);

        return $this;
    }

    /**
     * Converts string to lovercase.
     */
    public function toLower(): self
    {
        $this->value = strtolower($this->value);

        return $this;
    }

    /**
     * Converts string to uppercase.
     */
    public function toUpper(): self
    {
        $this->value = strtoupper($this->value);

        return $this;
    }

    /**
     * Returns whenever string contains given substring.
     */
    public function contains(string $substring): bool
    {
        return str_contains($this->value, $substring);
    }

    public function has(string $substring): bool
    {
        return $this->contains($substring);
    }

    /**
     * Returns whenever string starts with given substring.
     */
    public function startsWith(string $substring): bool
    {
        return str_starts_with($this->value, $substring);
    }

    /**
     * Returns whenever string ends with given substring.
     */
    public function endsWith(string $substring): bool
    {
        return str_ends_with($this->value, $substring);
    }

    /**
     * Replaces all occurences of given search string with replace string.
     */
    public function replace(string $search, string $replace): self
    {
        $this->value = str_replace($search, $replace, $this->value);

        return $this;
    }

    /**
     * Randomly shuffles string.
     */
    public function shuffle(): self
    {
        $this->value = str_shuffle($this->value);

        return $this;
    }

    /**
     * Reverses string.
     */
    public function reverse(): self
    {
        $this->value = strrev($this->value);

        return $this;
    }

    /**
     * Creates new String_ instance.
     */
    public static function from(string $subject): self
    {
        return new String_($subject);
    }
}
