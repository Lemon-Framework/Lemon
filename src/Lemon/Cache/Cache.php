<?php

namespace Lemon\Cache;

use Lemon\Kernel\Lifecycle;

class Cache
{
    public function __construct(Lifecycle $lifecycle)
    {
    }
}


/**



$c = new Cache($lifecycle);
$c->set('parke', 'rizek');
$c->get('parke');
$c->remove('parke');



 */
