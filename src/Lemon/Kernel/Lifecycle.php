<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Error;
use ErrorException;
use Exception;
use Lemon\Http\Request;
use Lemon\Support\Filesystem;
use Lemon\Support\Types\Str;
use Lemon\Zest;
use Throwable;

/**
 * The Lemon Lifecycle.
 */
final class Lifecycle extends Container
{
    /**
     * Current Lemon version.
     */
    public const VERSION = '3-develop';

    /**
     * Default units with aliases.
     */
    private const DEFAULTS = [
        \Lemon\Routing\Router::class => ['routing'],
        \Lemon\Config\Config::class => ['config'],
        \Lemon\Cache\Cache::class => ['cache', \Psr\SimpleCache\CacheInterface::class],
        \Lemon\Templating\Juice\Compiler::class => ['juice', \Lemon\Templating\Compiler::class],
        \Lemon\Templating\Factory::class => ['templating'],
        \Lemon\Support\Env::class => ['env'],
        \Lemon\Http\ResponseFactory::class => ['response'],
        \Lemon\Http\PhpSession::class => ['session', \Lemon\Http\Session::class],
        \Lemon\Http\Cookies::class => ['cookies'],
        \Lemon\Protection\Csrf::class => ['csrf'],
        \Lemon\Debug\Handling\Handler::class => ['handler'],
        \Lemon\Terminal\Terminal::class => ['terminal'],
        \Lemon\Debug\Dumper::class => ['dumper'],
        \Lemon\Events\Dispatcher::class => ['events'],
        \Lemon\Logging\Logger::class => ['log', \Psr\Log\LoggerInterface::class],
        \Lemon\Database\Database::class => ['database', \Lemon\Database\Database::class],
    ];

    /**
     * App directory.
     */
    public readonly string $directory;

    /**
     * Creates new lifecycle instance.
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->add(self::class, $this);
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    /**
     * Registers default services.
     */
    public function loadServices(): void
    {
        foreach (self::DEFAULTS as $unit => $aliases) {
            $this->add($unit);
            foreach ($aliases as $alias) {
                $this->alias($alias, $unit);
            }
        }
    }

    /**
     * Initializes zests.
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

    public function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Executes error handler.
     */
    public function handle(Throwable $problem): void
    {
        $this->get('handler')->handle($problem);

        exit;
    }

    /**
     * Loads fundamental commands.
     */
    public function loadCommands(): void
    {
        $commands = new Commands($this->get('terminal'));
        $commands->load();
    }

    /**
     * Returns path of specific file in current project.
     */
    public function file(string $path, string $extension = null): string
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

    public function runsInTerminal(): bool
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * Executes whole lifecycle.
     */
    public function boot(): void
    {
        if ('web' !== $this->get('config')->get('kernel.mode')) {
            return;
        }

        try {
            $this->get('routing')->dispatch($this->get(Request::class))->terminate();
        } catch (Exception|Error $e) {
            $this->handle($e);
        }
    }

    /**
     * Initializes whole application for you.
     */
    public static function init(string $directory, bool $terminal = true): self
    {
        // --- Creating Lifecycle instance ---
        $lifecycle = new self(Filesystem::parent($directory));

        // --- Loading default Lemon services ---
        $lifecycle->loadServices();

        // --- Loading Zests for services ---
        $lifecycle->loadZests();

        // --- Loading Error/Exception handlers ---
        $lifecycle->loadHandler();

        // --- Loading commands ---
        $lifecycle->loadCommands();

        /* --- The end ---
         * This function automaticaly boots our app at the end of file
         */
        register_shutdown_function(function () use ($lifecycle, $terminal) {
            /* --- Terminal ---
             * Once we run index.php from terminal via php index.php it will automaticaly start terminal
             * mode which will work instead of lemonade
             */
            if ($lifecycle->runsInTerminal()) {
                if ($terminal) {
                    $lifecycle->get('terminal')->run(array_slice($GLOBALS['argv'], 1));
                }

                return;
            }

            if (http_response_code() >= 500) {
                return;
            }

            // --- Obtaining request ---
            $lifecycle->add(Request::class, Request::capture()->injectLifecycle($lifecycle));

            $lifecycle->alias('request', Request::class);

            $lifecycle->boot();
        });

        return $lifecycle;
    }
}
