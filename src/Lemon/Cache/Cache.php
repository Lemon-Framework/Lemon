<?php

namespace Lemon\Cache;

use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem;

class Cache
{
    private Lifecycle $lifecycle;

    private array $data = [];

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
    }

    public function load()
    {
        $directory = $this->lifecycle->config('cache', 'storage');
        $path = $this->lifecycle->file($directory);
        if (!Filesystem::isDir($directory)) {
            Filesystem::makeDir($path);
            Filesystem::write(Filesystem::join($path, '.gitignore'), "*\n!.gitignore");
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function get(string $key)
    {
        // vezme hodnotu klice z cache a defaultniho souboru
    }

    public function set($key, $value, $expires = null)
    {
        // setne hodnotu do cache pripadne nastavi expiraci - nejaka datetime operace i guess PICI TIMEZONY
    }

    public function clear()
    {
        // smaze cache
    }
}

/*
 *
 *
 *
 * $c = new Cache($lifecycle);
 * $c->set('parke', 'rizek');
 * $c->get('parke');
 * $c->remove('parke');
 *
 *
 *
 */
