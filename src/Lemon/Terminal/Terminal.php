<?php

namespace Lemon\Terminal;

use Lemon\Kernel\Unit;

/**
 * @property \Lemon\Kernel\Lifecycle $lifecycle
 */
class Terminal extends Unit
{

    private ?StyleCollection $styles;

    public function width()
    {
        return (int) getenv('COLUMNS');
    }

    public function height()
    {
        return (int) getenv('ROWS');
    }

    public function getStyles()
    {
        if (!isset($this->styles))
            $this->styles = new StyleCollection($this);

        return $this->styles;
    }

    public function out($content)
    {
        $output = new Output($this, $content);
        $render = $output->parse();
        if ($this->lifecycle->config('init', 'mode') == 'web')
            return file_put_contents('php://stdout', $render);

        echo $render;
    }
}

