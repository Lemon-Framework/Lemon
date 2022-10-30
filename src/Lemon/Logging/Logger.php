<?php

declare(strict_types=1);

namespace Lemon\Logging;

use DateTime;
use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Logging\Logger as LoggerContract;
use Lemon\Support\Filesystem;
use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use Stringable;

class Logger extends AbstractLogger implements LoggerContract
{
    private string $destination;

    public function __construct(
        private Config $config
    ) {
        $this->resolveDestination();
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $level = strtoupper($level);
        if (!defined(LogLevel::class.'::'.$level)) {
            throw new InvalidArgumentException('Log level '.$level.' is not valid');
        }
        $message = $this->interpolate((string) $message, $context);

        $now = (new DateTime())->format('D M d h:i:s Y');
        file_put_contents($this->destination, sprintf("[%s] %s: %s\n", $now, $level, $message), FILE_APPEND);
    }

    /**
     * Compiles message with given context.
     */
    public function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{'.$key.'}', (string) $value, $message);
        }

        return $message;
    }

    /**
     * Resolves logging destination.
     */
    private function resolveDestination()
    {
        $this->destination = $this->config->file('logging.file', 'log');
        if (!Filesystem::isFile($this->destination)) {
            $dir = Filesystem::parent($this->destination);
            if (!is_dir($dir)) {
                Filesystem::makeDir($dir);
            }
            Filesystem::write(Filesystem::join($dir, '.gitignore'), "*\n!.gitignore");
        }
    }
}
