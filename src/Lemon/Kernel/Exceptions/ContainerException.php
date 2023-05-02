<?php

declare(strict_types=1);

namespace Lemon\Kernel\Exceptions;

use Lemon\Debug\Handling\Attributes\Doc;
use Psr\Container\ContainerExceptionInterface;

#[Doc('digging_deeper/lifecycle')]
class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
