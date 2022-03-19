<?php

declare(strict_types=1);

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

    public function load(): void
    {
        $directory = $this->lifecycle->config('cache', 'storage');
        $path = $this->lifecycle->file($directory);
        if (! Filesystem::isDir($directory)) {
            Filesystem::makeDir($path);
            Filesystem::write(Filesystem::join($path, '.gitignore'), "*\n!.gitignore");
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function get(string $key): void
    {
        // vezme hodnotu klice z cache a defaultniho souboru
    }

    public function set($key, $value, $expires = null): void
    {
        // setne hodnotu do cache pripadne nastavi expiraci - nejaka datetime operace i guess PICI TIMEZONY
    }

    public function clear(): void
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
