<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Juice\Nodes\SingletonDirective;

class ElseDirective extends SingletonDirective
{

    public function generate(SematicContext $context, Generators $generators): string 
    {
        return '<?php }else{ ?>';
    }
}
