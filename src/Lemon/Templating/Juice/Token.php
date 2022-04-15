<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;

class Token
{

    const TAG = 0,
          OUTPUT = 1,
          UNESCAPED = 2,
          TEXT = 3
    ;

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
