<?php

declare(strict_types=1);

namespace Lemon\Cache\Exceptions;

use Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;

class InvalidArgumentException extends CacheException implements PsrInvalidArgumentException
{
}
