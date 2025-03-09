<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating\Juice;

use Lemon\Templating\Juice\SematicContext;

interface Node
{
    // public function check()
    public function generate(SematicContext $context): string;
    // public function transpileClean()
}
