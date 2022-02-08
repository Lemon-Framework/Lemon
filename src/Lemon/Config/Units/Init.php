<?php

namespace Lemon\Config\Units;

/**
 * Initial configuration
 *
 * @property string $mode
 * @property string $debug
 */
class Init extends Unit
{
    public function __construct()
    {
        $this->data = [
            'mode' => LEMON_MODE,
            'debug' => false
        ];
    }
}
