<?php

namespace Lemon\Support\Filesystem;

use Lemon\Support\Types\String_;

class File
{
    /**
    * File path
     *
     * @var \Lemon\Support\Filesystem\Path
     */
    public Path $path;

    /**
     * Time of last file modification
     *
     * @var
     */
    public String_ $last_modified;

    /**
     * Creates new File instance
     *
     * @param String|\Lemon\Support\Types\String_ $path
     */
    public function __construct(String|String_ $path)
    {
        if (!is_file($path)) {
            file_put_contents($path, '');
        }

        $this->path = new Path($path);
        $this->last_modified = String_::from(
            date(
                'YY-DD-MMTh:i:s',
                filemtime($path)
            )
        );
    }

    /**
     * Returns file content
     *
     * @return \Lemon\Support\Types\String_
     */
    public function content(): String_
    {
        return String_::from(
            file_get_contents($this->path)
        );
    }

    /**
     * Saves given data
     *
     * @param String|\Lemon\Support\Types\String_ $content
     * @return \Lemon\Support\Filesystem\File
     */
    public function save(String|String_ $content): self
    {
        file_put_contents($this->path, $content);
        return $this;
    }

    /**
     * Deletes file and unsets instance
     */
    public function delete()
    {
        unlink($this->path);
        unset($this);
    }

    public function include()
    {
        include $this->path;
    }
}
