<?php

namespace Lemon\Support\Filesystem;

use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;
use Lemon\Support\Types\String_;

class Directory
{
    /**
     * Directory path
     *
     * @var \Lemon\Support\Types\String_
     */
    public Path $path;

    public Array_ $files;

    /**
     * Creates new Directory instance
     *
     * @param String|\Lemon\Support\Types\String_ $path
     */
    public function __construct(String|String_ $path)
    {
        $this->path = new Path($path);
        if ($this->path->isDir()) {
            return $this->files = $this->scan();
        }

        mkdir($this->path);
    }

    public function scan()
    {
        $files = new Array_();
        foreach (scandir($this->path) as $file) {
            if (Arr::contains(['.', '..'], $file)) {
                continue;
            }

            $full_path = $this->path->append(new Path($file));

            if ($full_path->isFile()) {
                $files->push($full_path->resolve());
            }

            if ($full_path->isDir()) {
                $files->merge($full_path->resolve()->scan());
            }
        }

        return $files;
    }

    public function delete()
    {
        foreach (scandir($this->path) as $file) {
            if (Arr::contains(['.', '..'], $file)) {
                continue;
            }

            $full_path = $this->path->append(new Path($file));

            if ($full_path->isFile()) {
                unlink($full_path);
            }

            if ($full_path->isDir()) {
                (new Directory($full_path))->delete();
            }
        }

        rmdir($this->path);
    }

    public function export()
    {
        $files = new Array_();
        foreach (scandir($this->path) as $file) {
            if (Arr::contains(['.', '..'], $file)) {
                continue;
            }

            $full_path = $this->path->append(new Path($file));

            if ($full_path->isFile()) {
                $files->push($full_path->resolve());
            }

            if ($full_path->isDir()) {
                $files->push($full_path->resolve()->export());
            }
        }

        return $files;
    }

    public function include()
    {
        foreach ($this->files as $file) {
            $file->include;
        }
    }

    public function put(String|String_ $target)
    {
        $target = String_::resolve($target);

        if ($target->endsWith('/')) {
            return new File($target);
        }
        return new Directory($target);
    }
}
