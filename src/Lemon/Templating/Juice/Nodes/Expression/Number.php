<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class Number implements Expression
{
    public function __construct(
        public readonly string $content,
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string
    {
        return $this->content;
    }
}
