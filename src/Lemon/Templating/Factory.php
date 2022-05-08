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
        $lifecycle->add(Enviroment::class);
        $lifecycle->alias('templating.env', Enviroment::class);

        $config = $config->part('templating');
        $this->templates = $config->file('location');
        $this->cached = $config->file('cached');
    }

    /**
     * Creates new template.
     */
    public function make(string $name, array $data = []): Template
    {
        $path = $this->getRawPath($name);
        $compiled_path = $this->getCompiledPath($name);
        $this->compile($path, $compiled_path);
    
        $data['_env'] = $this->lifecycle->get('templating.env');

        return new Template($path, $compiled_path, $data);
    }

    /**
     * Returns path of raw template.
     */
    public function getRawPath(string $name): string
    {
        $path = $this->templates.DIRECTORY_SEPARATOR.Str::replace($name, '.', DIRECTORY_SEPARATOR)->value.'.'.$this->compiler->getExtension();

        if (!FS::isFile($path)) {
            throw new TemplateException('Template '.$name.' does not exist');
        }

        return $path;
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
        if (!FS::isDir($this->cached)) {
            FS::makeDir($this->cached);
            FS::write($this->cached.DIRECTORY_SEPARATOR.'.gitignore', "*\n!.gitignore");
        }
        if (FS::isFile($compiled_path)) {
            if (filemtime($compiled_path) >= filemtime($raw_path)) { // This mechanism was taken from laravel.
                return;
            }
            FS::delete($compiled_path);
        }

        try {
            $compiled = $this->compiler->compile(file_get_contents($raw_path));
        } catch (Throwable $throwable) {
            throw TemplateException::from($throwable, $raw_path);
        }

        FS::write($compiled_path, $compiled);
    }
}
