<?php

namespace Lemon\Exceptions;

use Exception;

class FilesystemException extends Exception 
{
    public static function explainFileNotFound($file): self
    {
        return new self("File $file does not exist");
    }

    public static function explainDirectoryNotFound($directory): self
    {
        return new self("Directory $directory does not exist");
    }
}
