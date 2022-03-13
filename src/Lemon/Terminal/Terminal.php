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
    { // WIP
        $columns = getenv('COLUMNS');

        return is_numeric($columns) ? (int) $columns : 80;
    }

    public function height()
    {
        $rows = getenv('LINES');

        return is_int($rows) ? (int) $rows : 50;
    }

    public function getStyles()
    {
        if (!isset($this->styles)) {
            $this->styles = new StyleCollection($this);
        }

        return $this->styles;
    }

    public function out($content)
    {
        $output = new Output($this, $content);
        $render = $output->resolve();

        if ($render instanceof Output) {
            return $render;
        }

        $render .= PHP_EOL;

        if ('web' == $this->lifecycle->config('init', 'mode')) {
            return file_put_contents('php://stdout', $render);
        }

        echo $render;
    }
}
