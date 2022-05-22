<?php

declare(strict_types=1);

namespace Lemon\Http\Middlewares;

use Lemon\Config\Config;

class Cors
{
    public function handle(Config $config)
    {
        $origins = $config->part('http')->get('cors')['allow-origins'];

        // etcetera
        header('Access-Control-Allow-Origin: '.$this->getOrigins($origins));
    }

    private function getOrigins(string|array $origins): string
    {
        if (is_array($origins)) {
            // ???
            return implode(' ', $origins);
        }

        return $origins;
    }
}
