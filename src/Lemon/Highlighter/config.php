<?php

declare(strict_types=1);

use Lemon\Highlighter\Highlighter;

return [
    Highlighter::Declaration => 'style="color: #689d6a"',
    Highlighter::Statement => 'style="color: #cc241d"',
    Highlighter::Number => 'style="color: #b16286"',
    Highlighter::String => 'style="color: #98971a"',
    Highlighter::Type => 'style="color: #d79921"',
    Highlighter::Comment => 'style="color: ##28374"',
    Highlighter::Variable => 'style="color: #458588"',
    Highlighter::Default => 'style="color: #ebdbb2"',
];
