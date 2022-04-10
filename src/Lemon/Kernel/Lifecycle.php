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
final class Lifecycle extends Container
{
    /**
     * Current Lemon version.
     */
    public const VERSION = '3-develop';

    /**
     * App directory.
     */
    public readonly string $directory;

    /**
     * Dependency injection container for curent lifecycle
     */
    private Container $container;

    /**
     * Default units with aliases 
     */
    private array $default = [
        'routing' => \Lemon\Http\Routing\Router::class,
        'terminal' => \Lemon\Terminal\Terminal::class,
        'config' => \Lemon\Config\Config::class,            
        'cache' => \Lemon\Cache\Cache::class,
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
        foreach($this->default as $alias => $unit) {
            $this->add($unit);
            $this->alias($alias, $unit);
        }
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
        $matched = $this->get('config')->part($part);
        if ($key) {
            return $matched->{$key};
        }

        return $matched;
    }

    /**
     * Returns path of specific file in current project.
     */
    public function file(string $path, string $extension=null): string
    {
        $dir = Filesystem::join(
            $this->directory,
            Str::replace($path, '.', DIRECTORY_SEPARATOR)->value
        );

        $dir = Filesystem::normalize($dir);

        if ($extension) {
            return $dir.'.'.trim($extension, " \t\n\r.");
        }

        return $dir;
    }

    /**
     * Executes whole lifecycle.
     */
    public function boot(): void
    {
        try {
            $request = Request::make();
            $this->get('routing')->dispatch($request)->terminate();
        } catch (Exception|Error $e) {
            $this->handle($e);
        }
    }


    /**
     * Initializes whole application for you
     */
    public static function init(string $directory): self
    {
        /*--- Creating Lifecycle instance ---*/
        $lifecycle = new self($directory); 

        /*--- Loading default Lemon services ---*/
        $lifecycle->loadServices();

        /*--- Loading Zests for services ---*/       
        $lifecycle->loadZests();

        /* --- Loading Error/Exception handlers ---*/
        $lifecycle->loadHandler();

        /* --- The end ---
         * This function automaticaly boots our app at the end of file
         */
        register_shutdown_function(function() use ($lifecycle) {
            if (http_response_code() >= 500) {
                return;
            }
            $lifecycle->boot();
        });

        return $lifecycle;
    }
}
