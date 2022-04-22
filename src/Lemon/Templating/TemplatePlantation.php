<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem as FS;
use Lemon\Support\Types\Str;
use Lemon\Templating\Exceptions\TemplateException;
use Throwable;

/**
 * Manages and generates templates.
 */
class TemplatePlantation
{
    /**
     * Directory containing templates.
     */
    private string $templates;

    /**
     * Directory containing cached templates.
     */
    private string $cached;

    public function __construct(
        private Compiler $compiler,
        private Config $config,
        private Lifecycle $lifecycle
    ) {
        $config = $this->config->part('templating');
        $this->templates = $config->get('location');
        $this->cached = $config->get('cached');
    }

    /**
     * Creates new template.
     */
    public function make(string $template, array $data = []): Template
    {
        $source = $this->findSource($template);

        return new Template($source, $this->load($source, $template), $data); // TODO
    }

    /**
     * Compiles template into raw php.
     */
    public function compile(string $path): string
    {
        try {
            $content = file_get_contents($path);

            return $this->compiler->compile($content);
        } catch (Throwable $e) {
            throw new TemplateException($e, $path);
        }
    }

    private function findSource(string $template): string
    {
        return $this->lifecycle->file($this->templates.'.'.$template, $this->compiler->getExtension());
    }

    private function load(string $path, string $template): string
    {
        if (!FS::isDir($this->cached)) {
            FS::makeDir($this->cached);
        }
        $time = filemtime($path);
        $name = Str::replace($template, '.', '_');
        $file = FS::join($this->cached, "lemon_template_{$name}_{$time}.php");

        if (!FS::isFile($file)) {
            FS::write($file, $this->compile($path));
        }

        return $file;
    }
}
