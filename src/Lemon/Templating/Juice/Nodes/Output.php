<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class Output implements Node
{
    public function __construct( 
        public readonly Expression $expression, 
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string 
    {
        return 
            '<?php echo $_env->'
            .$context->escaping->getEscapingMethod()
            .'('
            .$this->expression->generate($context, $generators)
            .')'
            .' ?>'
        ;
    }
}
