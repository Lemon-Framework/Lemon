<?php

namespace Lemon\Support\Types;

use Stringable;

class String_ implements Stringable
{
    /** String content */
    public string $content;

    /**
     * Lemon string type.
     */
    public function __construct(string $subject)
    {
        $this->content = $subject;
    }

    public function __toString()
    {
        return $this->content;
    }

    /**
     * Returns size of string.
     */
    public function size(): int
    {
        return strlen($this->content);
    }

    /**
     * Returns size of string.
     */
    public function len(): int
    {
        return strlen($this->content);
    }

    /**
     * Splits string to array by separator.
     */
    public function split(string $separator): Array_
    {
        return new Array_(
            explode($separator, $this->content)
        );
    }

    /**
     * Joins given Array items with string.
     *
     * @param array|Array_ $array
     */
    public function join(array $array): self
    {
        return String_::from(
            implode($this->content, $array)
        );
    }

    /**
     * Converts first character to uppercase.
     */
    public function capitalize(): self
    {
        $this->content = ucfirst($this->content);

        return $this;
    }

    /**
     * Converts first character to lovercase.
     */
    public function decapitalize(): self
    {
        $this->content = lcfirst($this->content);

        return $this;
    }

    /**
     * Converts string to lovercase.
     */
    public function toLower(): self
    {
        $this->content = strtolower($this->content);

        return $this;
    }

    /**
     * Converts string to uppercase.
     */
    public function toUpper(): self
    {
        $this->content = strtoupper($this->content);

        return $this;
    }

    /**
     * Returns whenever string contains given substring.
     *
     * @param string|String_ $substring
     */
    public function contains(string $substring): bool
    {
        return str_contains($this->content, $substring);
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
        return str_starts_with($this->content, $substring);
    }

    /**
     * Returns whenever string ends with given substring.
     */
    public function endsWith(string $substring): bool
    {
        return str_ends_with($this->content, $substring);
    }

    /**
     * Replaces all occurences of given search string with replace string.
     *
     * @param string|String_ $search
     * @param string|String_ $replace
     *
     * @return String_
     */
    public function replace(string $search, string $replace): self
    {
        $this->content = str_replace($search, $replace, $this->content);

        return $this;
    }

    /**
     * Randomly shuffles string.
     *
     * @return String_
     */
    public function shuffle(): self
    {
        $this->content = str_shuffle($this->content);

        return $this;
    }

    /**
     * Reverses string.
     *
     * @return String_
     */
    public function reverse(): self
    {
        $this->content = strrev($this->content);

        return $this;
    }

    /**
     * Creates new String_ instance.
     *
     * @return
     */
    public static function from(string $subject): self
    {
        return new String_($subject);
    }
}
