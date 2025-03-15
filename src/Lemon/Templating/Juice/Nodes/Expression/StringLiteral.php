<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class StringLiteral implements Expression
{
    /**
     * @param array<string|Expression> $content
     */
    public function __construct(
        public readonly array $content,
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string
    {
        $result = '"';
        foreach ($this->content as $part) {
            if (is_string($part)) {
                $result .= $part;
                continue;
            }
            
            $result .= $part->generate($context, $generators);
        }

        return $result.'"';
    }
    
}
