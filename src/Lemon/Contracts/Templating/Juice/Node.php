<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating\Juice;

use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\SematicContext;

interface Node
{
    // public function check()
    public function generate(SematicContext $context, Generators $generators): string;
    // public function transpileClean()
}
