<?php

declare(strict_types=1);

namespace Lemon\Cache\Exceptions;

use Lemon\Debug\Handling\Attributes\Doc;
use Psr\SimpleCache\CacheException as PsrCacheException;

#[Doc('digging_deeper/caching')]
class CacheException extends \Exception implements PsrCacheException
{
}
