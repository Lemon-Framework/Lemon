<?php

namespace Lemon\Kernel;

use Error;
use ErrorException;
use Exception;
use Lemon\Config as ConfigZest;
use Lemon\Route as RouteZest;
use Lemon\Config\Config;
use Lemon\Exceptions\Handling\Handler;
use Lemon\Http\Request;
use Lemon\Http\Routing\Dispatcher;
use Lemon\Http\Routing\Router;
use Lemon\Support\Http\Routing\Route;
use Lemon\Support\Types\Str;
use Lemon\Zest;

/**
 * The Lemon Framework Lifecycle
 *
 * @property Config $config
 * @property Route $routing
 */
class Lifecycle
{
    /**
     * Current Lemon version
     *
     * @var string $directory
     */
    public const VERSION = '3-develop';

    /**
     * App directory
     * 
     * @var string $directory  
     */
    public readonly string $directory;

    /**
     * List of all Lifecycle components (Units)
     *
     * @var array $units 
     */
    private array $units = [
        'config' => [Config::class],
        'routing' => [Router::class]
    ];

    /**
     * Creates new lifecycle instance
     *
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function loadZests(): void
    {
        Zest::init($this);
    }


    /**
     * Loads error/exception handlers
     *
     * @return void
     */
    public function loadHandler(): void
    {
        error_reporting(-1);
        set_error_handler([$this, 'handleError']); 
        set_exception_handler([$this, 'handle']);
    }

    public function handleError($level, $message, $file = '', $line  =0, $context = [])
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Executes error handler depending on lemon mode
     */
    public function handle($problem)
    {
        $handler = new Handler($problem, $this);
        $handler->terminate();
        exit;
    }

    /**
     * Returns config unit instance or config value from given unit
     *
     * @param string $unit
     * @param string $key?
     * @return mixed
     */
    public function config(string $unit, string $key=null): mixed
    {
        $matched = $this->config->{'get' . Str::capitalize($unit)}(); 
        if ($key)
            return $matched->$key;

        return $matched;
    }

    public function addUnit($name, $unit)
    {
        $this->units[$name] = [$unit];
        return $this;
    }

    public function unit($name)
    {
        if (!isset($this->units[$name][1]))
            $this->units[$name][1] = new $this->units[$name][0]($this);

        return $this->units[$name][1];
    }

    /**
     * Returns loaded unit instance
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name): mixed
    {
        if (!isset($this->units[$name]))
            throw new Exception('Unit ' . $name . ' does not exist');
        
        return $this->unit($name);
    }

    /**
     * Executes whole lifecycle
     */
    public function boot()
    {
        try
        {
            $request = Request::make();
            $this->routing->dispatch($request)->terminate();
        }
        catch (Exception|Error $e)
        {
            $this->handle($e);
        }
    }
}
