<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

class OutputCompiler
{
    public function compileEcho(string $content, int $context): string
    {
        return '<?= $_env->escape('.$content.') ?>'; 
    }        

    public function compileUnescaped(string $content): string
    {
        return '<?= '.$content.' ?>';
    }
}
