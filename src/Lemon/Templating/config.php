<?php

declare(strict_types=1);

use Lemon\Templating\Juice\HtmlNodes;
use Lemon\Templating\Juice\Directives;
use Lemon\Templating\Juice\Operators;
use Lemon\Templating\Juice\Syntax;

return [
    'cached' => 'storage.templates',
    'location' => 'templates',
    'juice' => [
        'syntax' => new Syntax(),
        'operators' => new Operators(),
        'directives' => new Directives(),
        'html-nodes' => new HtmlNodes(),
    ],
];
