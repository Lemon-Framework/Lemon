<?php

namespace Lemon\Kernel;

use Error;
use ErrorException;
use Exception;
use Lemon\Config\Config;
use Lemon\Exceptions\Handling\Handler;
use Lemon\Http\Request;
use Lemon\Http\Routing\Router;
use Lemon\Support\Filesystem;
use Lemon\Support\Http\Routing\Route;
use Lemon\Support\Types\Str;
use Lemon\Terminal\Terminal;
use Lemon\Zest;

/**
 * The Lemon Framework Lifecycle.
 *
 * @property Config   $config
 * @property Route    $routing
 * @property Terminal $terminal
 */
class Lifecycle
{
    /**
     * Current Lemon version.
     *
     * @var string
     */
    public const VERSION = '3-develop';

    /**
     * App directory.
     */
    public readonly string $directory;

    /**
     * List of all Lifecycle components (Units).
     */
    private array $units = [
        'config' => [Config::class],
        'routing' => [Router::class],
        'terminal' => [Terminal::class],
    ];

    /**
     * Creates new lifecycle instance.
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function __get(string $name): mixed
    {
        if (!isset($this->units[$name])) {
            throw new Exception('Unit '.$name.' does not exist');
        }

        return $this->unit($name);
    }

    public static function init()
    {
    }

    public function loadZests(): void
    {
        Zest::init($this);
    }

    /**
     * Loads error/exception handlers.
     */
    public function loadHandler(): void
    {
        error_reporting(-1);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handle']);
    }

    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Executes error handler.
     *
     * @param mixed $problem
     */
    public function handle($problem)
    {
        $handler = new Handler($problem, $this);
        $handler->terminate();

        exit;
    }

    /**
     * Returns config part or item from config part.
     *
     * @param string $unit
     * @param string $key?
     */
    public function config(string $part, string $key = null): mixed
    {
        $matched = $this->config->part($part);
        if ($key) {
            return $matched->{$key};
        }

        return $matched;
    }

    /**
     * Returns path of specific file in current project.
     *
     * @param string @path
     */
    public function file(string $path): string
    {
        return Filesystem::join(
            $this->directory,
            Str::replace($path, '.', DIRECTORY_SEPARATOR)
        );
    }

    /**
     * Adds unit.
     */
    public function addUnit(string $name, string $unit): self
    {
        $this->units[$name] = [$unit];

        return $this;
    }

    /**
     * Returns unit instance.
     *
     * @return mixed
     */
    public function unit(string $name)
    {
        if (!isset($this->units[$name][1])) {
            $this->units[$name][1] = new $this->units[$name][0]($this);
        }

        return $this->units[$name][1];
    }

    /**
     * Executes whole lifecycle.
     */
    public function boot()
    {
        try {
            $request = Request::make();
            $this->routing->dispatch($request)->terminate();
        } catch (Exception|Error $e) {
            $this->handle($e);
        }
    }
}
