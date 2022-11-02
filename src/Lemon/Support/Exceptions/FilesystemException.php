<?php

declare(strict_types=1);

namespace Lemon\Support\Exceptions;

class FilesystemException extends \Exception
{
    public static function explainFileNotFound($file): self
    {
        return new self("File {$file} does not exist");
    }

    public static function explainDirectoryNotFound($directory): self
    {
        return new self("Directory {$directory} does not exist");
    }
}
