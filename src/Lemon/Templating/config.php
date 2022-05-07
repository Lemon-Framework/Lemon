<?php

declare(strict_types=1);

use Lemon\Templating\Juice\Syntax;

return [
    'cached' => 'storage.templates',
    'location' => 'templates',
    'juice' => [
        'syntax' => new Syntax() 
    ],
];
