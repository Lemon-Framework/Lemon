<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Templating\Exceptions\TemplateException;

class Layout
{
    private array $blocks = [];

    private ?string $last = null;

    public function __construct(
        private string $file,
    ) {
    }

    public function __destruct()
    {
        $_layout = $this;

        if ($this->last) {
            throw new TemplateException('Unclosed block '.$this->last);
        }

        include $this->file;
    }

    public function startBlock(string $name)
    {
        if ($this->last) {
            throw new TemplateException('Unable to create block, because one is already openned');
        }
        $this->blocks[$name] = null;
        $this->last = $name;
        ob_start();
    }

    public function endBlock()
    {
        if (is_null($this->last)) {
            throw new TemplateException('Unable to close block, because there is no opened block');
        }
        $this->blocks[$this->last] = ob_get_clean();
        $this->last = null;
    }

    public function yield(string $name)
    {
        if (!isset($this->blocks[$name])) {
            throw new TemplateException('Undefined block '.$name);
        }
        echo trim($this->blocks[$name]);
    }
}
