<?php

namespace Lemon\Terminal;

use Lemon\Kernel\Lifecycle;
use Lemon\Kernel\Unit;

/**
 * @property \Lemon\Kernel\Lifecycle $lifecycle
 */
class Terminal extends Unit
{
    public function width()
    {
        return (int) getenv('COLUMNS');
    }

    public function height()
    {
        return (int) getenv('ROWS');
    }

    public function out($content)
    {
        $output = new Output($this, $content);
        $render = $output->render();
        if ($this->lifecycle->config('init', 'mode') == 'web')
            return file_put_contents('php://stdout', $render);

        echo $render;
    }
}

