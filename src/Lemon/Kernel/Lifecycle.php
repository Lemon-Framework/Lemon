<?php

namespace Lemon\Kernel;

use Error;
use Exception;
use Lemon\Config\Config;
use Lemon\Exceptions\Handling\Handler;
use Lemon\Support\Http\Routing\Route;
use Lemon\Support\Types\Str;

/**
 * The Lemon Framework Lifecycle
 *
 * @property string $config
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
    public string $directory;

    /**
     * List of all Lifecycle components (Units)
     *
     * @var array $units 
     */
    public array $units = [
        'config' => [Config::class]
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

    /**
     * Loads all Lemon components (Units)
     *
     * @return void
     */
    public function loadUnits(): void
    {
        foreach ($this->units as $unit => [$class])
            $this->units[$unit][1] = new $class($this);
    }

    /**
     * Loads error/exception handlers
     *
     * @return void
     */
    public function loadHandler(): void
    {
        set_exception_handler([$this, 'handle']);
        set_error_handler([$this, 'handle']); 
    }

    /**
     * Executes error handler depending on lemon mode
     */
    public function handle($problem)
    {
        if ($this->config('init', 'mode') == 'web')
        {
            $handler = new Handler($problem, $this);
            $handler->terminate();
        }
        // TODO console errors 
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
        return $this->units[$name][1];
    }

    /**
     * Executes whole lifecycle
     */
    public function boot()
    {
        try
        {
            Route::execute(); // WIP
        }
        catch (Exception|Error $e)
        {
            $this->handle($e);
        }
    }
}
