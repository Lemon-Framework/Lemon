<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\Expression\BinaryOperation;
use Lemon\Templating\Juice\Nodes\Expression\FunctionCall;
use Lemon\Templating\Juice\Nodes\Expression\FunctionName;
use Lemon\Templating\Juice\Nodes\PairDirective;
use Lemon\Templating\Juice\SematicContext;
use function PHPUnit\Framework\throwException;

class EachDirective extends PairDirective
{
    public function generate(SematicContext $context, Generators $generators): string 
    {
        // TODO range
        if (!($this->expression instanceof BinaryOperation)) {
            throw new CompilerException('Unexpected expression, expected as inside foreach', $this->position);
        }

        if (($l = $this->expression->left) instanceof FunctionCall
            && $l->function instanceof FunctionName
            && $l->function->name === 'range') {
            // TODO support both directions and some fancy stuff
            if (count($args = $l->arguments->nodes()) < 2) {
                throw new CompilerException('Expected at least 2 arguments for function range!', $l->position);
            }
            $var = $this->expression->right->generate($context, $generators);
            return 
                '<?php for ('
                .$var.'='.$args[0]
                .';'.$var.'<='.$args[1]
                .';'.$var.($args[3] ? '+='.$args[3] : '++')
                .') { ?>'
                .$this->body->generate($context, $generators)  
                .'<?php } ?>'
            ;
        } 

        return 
            '<?php foreach('
            .$this->expression->left->generate($context, $generators)
            .' as '
            .$this->expression->right->generate($context, $generators)
            .'){ ?>'
            .$this->body->generate($context, $generators)
            .'<?php } ?>'
        ;
    }
}
