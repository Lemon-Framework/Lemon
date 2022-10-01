<?php

declare(strict_types=1);

namespace Lemon\Terminal\IO;

use Lemon\Templating\Template;
use Lemon\Terminal\Exceptions\IOException;
use Lemon\Terminal\IO\Html\HtmlOutput;

class Output
{
    private HtmlOutput $html;

    public function __construct()
    {
        $this->html = new HtmlOutput();
    }

    public function out(mixed $content): string
    {
        if ($content instanceof Template) {
            return $this->out($content->render());
        }

        if (is_string($content)) {
            if (strip_tags($content) != $content) {
                return $this->html->compile($content);
            }
        }

        if (is_scalar($content)) {
            return (string) $content;
        }

        $type = gettype($content);

        throw new IOException('Value of type '.('object' == $type ? get_class($content) : $type).' could not be outputed');
    }
}
