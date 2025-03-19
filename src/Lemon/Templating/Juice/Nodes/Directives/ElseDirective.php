<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\SingletonDirective;
use Lemon\Templating\Juice\SematicContext;

class ElseDirective extends SingletonDirective
{

    public function generate(SematicContext $context, Generators $generators): string 
    {
        return '<?php }else{ ?>';
    }
}
