<?php

namespace Lemon\Support\Filesystem;

use Lemon\Exceptions\FileNotFoundException;
use Lemon\Support\Types\String_;

class Path
{
    public String_ $path;

    public function __construct(String|String_ $path)
    {
        $this->path = String_::resolve($path);
    }

    public function export(String|String_ $signature, String|String_ $suffix = '', String|String_ $separator = '.')
    {
        $signature = String_::resolve($signature);
        $this->path = $signature->replace($separator, DIRECTORY_SEPARATOR). $suffix;
        return $this;
    } 

    public function resolve()
    {
        if ($this->isFile())
            return new File($this->path);
        if ($this->isDir())
            return new Directory($this->path);

        throw new FileNotFoundException('File {$this->path} does not exist!');
    }

    public function append(Path $path)
    {
        $this->path = $this->path . $path->path->endTrim('/'); 
        return $this;
    }

    public function __toString()
    {
        return $this->path;
    }

    public function isFile()
    {
        return is_file($this->path);
    }

    public function isDir()
    {
        return is_dir($this->path);
    }

}
