<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Error;
use ErrorException;
use Exception;
use Lemon\Exceptions\Handling\Handler;
use Lemon\Exceptions\LifecycleException;
use Lemon\Http\Request;
use Lemon\Support\Filesystem;
use Lemon\Support\Http\Routing\Route;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;
use Lemon\Zest;

/**
 * The Lemon Lifecycle 
 */
final class Lifecycle
{
    /**
     * Current Lemon version.
     */
    public const VERSION = '3-develop';

    /**
     * App directory.
     */
    public readonly string $directory;

    private Container $container;

    private array $aliases = [
        'routing' => \Lemon\Http\Routing\Router::class,
        'terminal' => \Lemon\Terminal\Terminal::class,
        'config' => \Lemon\Config\Config::class,            
    ];

    /**
     * Creates new lifecycle instance.
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->container = new Container();
    }

    /**
     * Registers default services
     */
    public function loadServices(): void
    {
        foreach(Arr::values($this->aliases) as $unit) {
            $this->container->addService($unit);
        }
    }

    /**
     * Returns given Unit
     *
     * @throws \Lemon\Exceptions\LifecycleException
     */
    public function unit(string $alias): mixed
    {
        if (! isset($this->aliases[$alias])) {
            throw new LifecycleException('Unit '.$alias.' does not exist');
        }

        return $this->container->getService($this->aliases[$alias]);
    }

    /**
     * Adds new Unit
     *
     * @throws \Lemon\Exceptions\LifecycleException
     */
    public function addUnit(string $alias, string $class): static
    {
        if (isset($this->aliases[$alias])) {
            throw new LifecycleException('Unit '.$alias.' already exist');
        }

        $this->container->addService($class);
        $this->aliases[$alias] = $class;
        return $this;
    }

    /**
     * Initializes zests
     */
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

    public function handleError($level, $message, $file = '', $line = 0, $context = []): void
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Executes error handler.
     */
    public function handle(mixed $problem): void
    {
        $handler = new Handler($problem, $this);
        $handler->terminate();

        exit;
    }

    /**
     * Returns config part or item from config part.
     */
    public function config(string $part, ?string $key = null): mixed
    {
        $matched = $this->unit('config')->part($part);
        if ($key) {
            return $matched->{$key};
        }

        return $matched;
    }

    /**
     * Returns path of specific file in current project.
     */
    public function file(string $path): string
    {
        return Filesystem::join(
            $this->directory,
            Str::replace($path, '.', DIRECTORY_SEPARATOR)->value
        );
    }

    /**
     * Executes whole lifecycle.
     */
    public function boot(): void
    {
        try {
            $request = Request::make();
            $this->unit('routing')->dispatch($request)->terminate();
        } catch (Exception|Error $e) {
            $this->handle($e);
        }
    }


    /**
     * Initializes whole application for you
     */
    public static function init(string $directory): self
    {
        // TODO some fancy comments
        $lifecycle = new Lifecycle($directory); 
        $lifecycle->loadServices();
        $lifecycle->loadZests();
        $lifecycle->loadHandler();

        register_shutdown_function(function() use ($lifecycle) {
            if (http_response_code() >= 500) {
                return;
            }
            $lifecycle->boot();
        });

        return $lifecycle;
    }
}
