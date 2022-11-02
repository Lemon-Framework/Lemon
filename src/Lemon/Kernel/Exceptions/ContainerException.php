<?php

declare(strict_types=1);

namespace Lemon\Kernel\Exceptions;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
}
