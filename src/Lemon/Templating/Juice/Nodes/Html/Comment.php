<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Templating\Juice\Position;

class Comment
{
    public function __construct(
        public readonly string $content, 
        public readonly Position $position,
    ) {

    }

    // wait comment can contain stuff this is unfinished wtf
}
