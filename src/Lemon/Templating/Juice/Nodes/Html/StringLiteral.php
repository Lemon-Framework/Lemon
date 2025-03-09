<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class StringLiteral implements Node
{
    public function __construct(
        public readonly string $content,
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context): string 
    {
        return $this->content;
    }
}
