<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\PairDirective;
use Lemon\Templating\Juice\SematicContext;

class EachDirective extends PairDirective
{
    public function generate(SematicContext $context, Generators $generators): string 
    {
        if (!($this->expression instanceof BinaryOperation)) {
            throw new CompilerException('Unexpected expression, expected as inside foreach', $this->position);
        }

        return 
            '<?php foreach('
            .$this->expression->generate($context, $generators)
            .'){ ?>'
            .$this->body->generate($context, $generators)
            .'<?php } ?>'
        ;
    }
}
