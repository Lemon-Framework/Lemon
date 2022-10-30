<?php

declare(strict_types=1);

namespace Lemon\Templating;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Templating\Compiler;
use Lemon\Contracts\Templating\Factory as FactoryInterface;
use Lemon\Kernel\Application;
use Lemon\Support\Filesystem as FS;
use Lemon\Templating\Exceptions\TemplateException;
use Throwable;

/**
 * Manages and generates templates.
 */
final class Factory implements FactoryInterface
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
        private Application $application
    ) {
        $application->add(Environment::class);
        $application->alias('templating.env', Environment::class);

        $this->templates = $config->file('templating.location');
        $this->cached = $config->file('templating.cached');
    }

    /**
     * Creates new template.
     */
    public function make(string $name, array $data = []): Template
    {
        $path = $this->getRawPath($name);
        if (!$path) {
            throw new TemplateException('Template '.$name.' does not exist');
        }
        $compiled_path = $this->getCompiledPath($name);
        $this->compile($path, $compiled_path);

        $data['_env'] = $this->application->get('templating.env');
        $data['_factory'] = $this;

        return new Template($path, $compiled_path, $data);
    }

    /**
     * Returns path of raw template.
     */
    public function getRawPath(string $name): string|false
    {
        $path = $this->templates.DIRECTORY_SEPARATOR.str_replace('.', DIRECTORY_SEPARATOR, $name).'.'.$this->compiler->getExtension();

        if (!FS::isFile($path)) {
            return false;
        }

        return $path;
    }

    /**
     * Returns path of compiled template.
     */
    public function getCompiledPath(string $name): string
    {
        return $this->cached.DIRECTORY_SEPARATOR.str_replace('.', '_', $name).'.php';
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

    /**
     * Returns whenver template exists.
     */
    public function exist(string $template): bool
    {
        return $this->getRawPath($template) ? true : false;
    }
}
