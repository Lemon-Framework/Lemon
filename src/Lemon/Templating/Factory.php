<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem as FS;
use Lemon\Support\Types\Str;

/**
 * Manages and generates templates.
 */
class Factory
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
        Config $config,
        private Compiler $compiler,
        private Lifecycle $lifecycle
    ) {
        $config = $config->part('templating');
        $this->templates = $config->get('location'); // TODO rwritee config
        $this->cached = $config->get('cached');
    }

    /**
     * Creates new template.
     */
    public function make(string $name, array $data = []): Template
    {
        $path = $this->getRawPath($name);
        $compiled_path = $this->getCompiledPath($name);
        $this->compile($path, $compiled_path);

        return new Template($path, $compiled_path, $data);
    }

    /**
     * Returns path of raw template.
     */
    public function getRawPath(string $name): string
    {
        return $this->templates.DIRECTORY_SEPARATOR.Str::replace($name, '.', DIRECTORY_SEPARATOR)->value.'.'.$this->compiler->getExtension();
    }

    /**
     * Returns path of compiled template.
     */
    public function getCompiledPath(string $name): string
    {
        return $this->cached.DIRECTORY_SEPARATOR.Str::replace($name, '.', '_').'.php';
    }

    /**
     * Compiles template and caches it.
     */
    public function compile(string $raw_path, string $compiled_path): void
    {
        if (FS::isFile($compiled_path)) {
            if (filemtime($compiled_path) == filemtime($raw_path)) {
                return;
            }
            FS::delete($compiled_path);
        }
        touch($compiled_path, filemtime($raw_path));
        FS::write($compiled_path, $this->compiler->compile($raw_path));
    }
}
