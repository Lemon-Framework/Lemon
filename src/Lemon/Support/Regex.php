<?php

declare(strict_types=1);

namespace Lemon\Support;

final class Regex
{
    /**
     * Returns position from regex offset.
     */
    public static function getLine(string $subject, int $offset): int
    {
        if ($offset < 0) {
            return [$offset, $offset];
        }

        $line = 1;
        foreach (str_split($subject) as $char) {
            if ("\n" == $char) {
                ++$line;
            }
            if (0 == $offset) {
                break;
            }
            --$offset;
        }

        return $line;
    }
}
