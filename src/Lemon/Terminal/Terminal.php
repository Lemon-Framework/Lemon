<?php

namespace Lemon\Terminal;

use Lemon\Kernel\Lifecycle;

class Terminal 
{

    private Lifecycle $lifecycle;

    private ?StyleCollection $styles;

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
    }

    public function width()
    {
        return ((int) exec('tput cols')) ?? 80;
    }

    public function height()
    {
        return ((int) exec('tput rows')) ?? 50;
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
        $render = $output->resolve();

        if ($render instanceof Output)
            return $render;

        $render .= PHP_EOL;

        if ($this->lifecycle->config('init', 'mode') == 'web')
            return file_put_contents('php://stdout', $render);

        echo $render;
    }
}

