<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

/**
 * Represents template Token.
 *
 * @property int                  $kind
 * @property array<string>|string $content
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
        private string|array $content
    ) {
    }
}
