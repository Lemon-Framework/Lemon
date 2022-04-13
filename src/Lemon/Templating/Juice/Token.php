<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

class Token
{
    use Properties;

    public function __construct(
        #[Read]
        private int $kind,

        #[Read]
        private string $context,
    )
    {
        
    }
}
