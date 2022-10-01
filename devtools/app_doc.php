<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Lemon\Kernel\Application;

$result = '';

foreach (Application::DEFAULTS as $class => $aliases) {
    $result .= ' * @property-read '.$class.' $'.$class.PHP_EOL;
    foreach ($aliases as $alias) {
        $result .= ' * @property-read '.$class.' $'.$alias.PHP_EOL;
    }
}

echo $result;
