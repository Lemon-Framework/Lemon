<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Debug Zest
 * Provides static layer over the Lemon Debugging.
 *
 * @method static string resolve(mixed $data) Resolves parsing method depending on datatype.
 * @method static string build(mixed $data)   Builds html for dumper.
 * @method static void   dump(mixed $data)    Dumps given data.
 *
 * @see \Lemon\Debug\Dumper
 */
class Debug extends Zest
{
    public static function unit(): string
    {
        return 'dumper';
    }
}
