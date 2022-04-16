<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Tags;

interface Tag
{
    /**
     * Compiles openning tag
     *
     * @throws \Lemon\Templating\Juice\Exceptions\CompilerException
     */
    public function compileOpenning(string $content): string;
}
