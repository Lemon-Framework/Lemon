<?php

namespace Lemon\Exceptions;

use Exception;

class DirectoryNotFoundException extends Exception
{
    public static function explain($directory)
    {
        throw new self("Directory $directory does not exist");
    }
}
