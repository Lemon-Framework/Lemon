<?php

declare(strict_types=1);

namespace Lemon\Support;

final class Regex
{
    /**
     * Returns position from regex offset
     */
    public static function getLine(string $subject, int $offset): int
    {
        if ($offset < 0) {
            return [$offset, $offset];
        }

        $line = 1;
        foreach (str_split($subject) as $char) {
            if ($char == "\n") {
                $line++;
            }
            if ($offset == 0) {
                break;
            }
            $offset--;
        }

        return $line;
    }
}
