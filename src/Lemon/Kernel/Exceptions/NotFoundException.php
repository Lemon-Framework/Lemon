<?php

declare(strict_types=1);

namespace Lemon\Kernel\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
