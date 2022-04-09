<?php

declare(strict_types=1);

namespace Lemon\Kernel\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class NotFoundException extends Exception implements ContainerExceptionInterface 
{
}
