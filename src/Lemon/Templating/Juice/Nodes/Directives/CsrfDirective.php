<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Directives;

use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\SingletonDirective;
use Lemon\Templating\Juice\SematicContext;

class CsrfDirective extends SingletonDirective
{
    public function generate(SematicContext $context, Generators $generators): string 
    {
        return '<input type="hidden" name="CSRF_TOKEN" value="<?php echo \Lemon\Csrf::getToken() ?>">'; 
    }
}
