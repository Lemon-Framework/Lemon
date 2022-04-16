<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

class Token
{
    use Properties;

    public const TAG = 0;
    public const OUTPUT = 1;
    public const UNESCAPED = 2;
    public const TEXT = 3
    ;

    public function __construct(
        #[Read]
        private int $kind,
        #[Read]
        private string $context,
    ) {
    }
}
