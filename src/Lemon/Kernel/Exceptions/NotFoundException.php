<?php

declare(strict_types=1);

namespace Lemon\Kernel\Exceptions;

use Lemon\Debug\Handling\Attributes\Doc;
use Psr\Container\NotFoundExceptionInterface;

#[Doc('digging_deeper/lifecycle')]
class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
