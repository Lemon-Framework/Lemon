<?php


namespace Lemon\Config;

use Exception;
use Lemon\Kernel\Lifecycle;

/**
 * Main interface for storing config data in organised way
 *
 * @method \Lemon\Config\Units\Init getInit() 
 */
class Config 
{
    /**
     * Lifecycle config unit belongs to.
     * 
     * @var Lifecycle $lifecycle
     */
    public Lifecycle $lifecycle;

    /**
     * Array of all config units (components)
     */
    public array $units = [
        'Init' => null
    ];

    /**
     * Creates new config instance
     *
     * @param Lifecycle $lifecycle
     */
    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
        $this->loadUnits();
    }

    /**
     * Loads all config units
     *
     * @return void
     */
    private function loadUnits(): void
    {
        $base = __NAMESPACE__;
        foreach ($this->units as $unit => $_)
            $this->units[$unit] = new ($base . '\\Units\\' . $unit)($this);
    } 

    public function __call($name, $_)
    {
        if (preg_match('/get([A-Z][a-z]+)/', $name, $matches))
            return $this->units[$matches[1]]; 

        throw new Exception('Call to undefined method Config::' . $name . '()');
    }
}
