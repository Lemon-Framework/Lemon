<?php

declare(strict_types=1);

use Lemon\Templating\Juice\Syntax;

return [
    'juice' => [
        'syntax' => new Syntax(),
    ],
    'cached' => 'storage'.DIRECTORY_SEPARATOR.'templates',
    'location' => 'templates',
];
