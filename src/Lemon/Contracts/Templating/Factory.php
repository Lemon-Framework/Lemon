<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating;

use Lemon\Templating\Template;

interface Factory
{
    /**
     * Creates new template.
     */
    public function make(string $name, array $data = []): Template;

    /**
     * Returns whenver template exists.
     */
    public function exist(string $template): bool;
}
