<?php

declare(strict_types=1);

namespace Lemon\Terminal\IO\Html;

use DOMNode;
use DOMText;
use Lemon\Terminal\Exceptions\HtmlException;

class Components
{
    public readonly Styles $styles;

    public function __construct() 
    {
        $this->styles = new Styles();    
    }

    public function parse(DOMNode $element): string
    {
        $result = '';
        foreach ($element->childNodes as $child) {
            [$inherit, $open, $close] = $this->styles->getStyle($element);
            $result .= $inherit.$open.$this->parseElement($child).$close.$inherit;
        }

        return $result."\033[0m";
    }

    public function parseElement(DOMNode $element): string
    {
        if ($element instanceof DOMText) {
            return $element->textContent;
        }

        $name = $element->nodeName;
        $method = 'parse'.ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->{$method}($element);
        }

        throw new HtmlException('Html tag '.$name.' is not supported');
    }

    public function parseDiv(DOMNode $element): string
    {
        return $this->parse($element);
    }

    public function parseH1(DOMNode $element): string
    {
        $content = $this->parse($element);
        $line = '+'.str_repeat('-', strlen($content) + 2).'+';

        return $line.PHP_EOL.'| '.$content.' |'.PHP_EOL.$line;
    }

    public function parseHr(): string
    {
        return str_repeat('-', 0); // TODO get size
    }
}
