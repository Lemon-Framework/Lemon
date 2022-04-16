<?php

declare(strict_types=1);

namespace Lemon\Cache\Exceptions;

use Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;

class InvalidArgumentException extends CacheException implements PsrInvalidArgumentException
{
}
