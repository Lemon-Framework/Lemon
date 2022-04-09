<?php

declare(strict_types=1);

namespace Lemon\Cache\Exceptions;

use Exception;
use Psr\SimpleCache\CacheException as PsrCacheException;

class CacheException extends Exception implements PsrCacheException
{

}
