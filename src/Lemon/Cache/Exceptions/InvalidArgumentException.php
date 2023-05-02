<?php

declare(strict_types=1);

namespace Lemon\Cache\Exceptions;

use Lemon\Debug\Handling\Attributes\Doc;
use Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;

#[Doc('digging_deeper/caching')]
class InvalidArgumentException extends CacheException implements PsrInvalidArgumentException
{
}
