<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\PairDirective;
use Lemon\Templating\Juice\SematicContext;

class IfDirective extends PairDirective
{
    public function generate(SematicContext $context, Generators $generators): string 
    {
        // tbd
        return 
            '<?php if('
            .$this->expression->generate($context, $generators)
            .'){ ?>'
            .$this->body->generate($context, $generators)
            .'<?php } ?>'
        ;
    }
}
