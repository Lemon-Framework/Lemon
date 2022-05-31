<?php

declare(strict_types=1);

namespace Lemon\Logging;

use Lemon\Config\Config;
use Lemon\Support\Types\Str;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Stringable;

class Logger extends AbstractLogger
{
    private int $type = 0;
    private string $destination = null;

    public function __construct(
        private Config $config
    ) {
        $this->resolveContext();    
    }

    private function resolveContext()
    {
        if ($this->config->part('kernel')->get('mode') == 'debug') {
            $this->type = 3;
            $this->destination = $this->config->part('logging')->file('file', 'log');
        }
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $level = Str::toUpper($level)->value;
        if (!defined(LogLevel::class.'::'.$level)) {
            throw new InvalidArgumentException('Log level'.$level.'is not valid');
        }
        $message = $this->interpolate((string) $message, $context);

        error_log("{$level}: {$message}", $this->type, $this->destination);
    }

    public function interpolate(string $message, array $context)
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{'.$key.'}', $value, $message);
        }
        return $message;
    }
}
