<?php

namespace Lemon\Cache;

use Lemon\Kernel\Lifecycle;

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
        $file = $this->lifecycle
        // nacte vec
    }

    public function getData()
    {
        if (!$this->data)
        
    }

    public function get(string $key)
    {
        // vezme hodnotu klice z cache a defaultniho souboru
    }

    public function set($key, $value, $expires=null)
    {
        // setne hodnotu do cache pripadne nastavi expiraci - nejaka datetime operace i guess PICI TIMEZONY
    }

    public function clear()
    {
        // smaze cache
    }


}


/**



$c = new Cache($lifecycle);
$c->set('parke', 'rizek');
$c->get('parke');
$c->remove('parke');



 */
