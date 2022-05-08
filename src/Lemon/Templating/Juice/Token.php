<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

/**
 * Represents template Token.
 *
 * @property-read int                  $kind
 * @property-read array<string>|string $content
 * @property-read int                  $line
 */
class Token
{
    use Properties;

    public const TAG = 0;
    public const TAG_END = 1;
    public const OUTPUT = 2;
    public const UNESCAPED = 3;
    public const TEXT = 4;

    public function __construct(
        #[Read]
        private int $kind,
        #[Read]
        private string|array $content,
        #[Read]
        private int $line,
    ) {
    }
}
