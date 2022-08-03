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
            return self::removeWhitespace($element);
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
        $line = '+'.str_repeat('-', self::lenght($content) + 2).'+';

        return $line.PHP_EOL.'| '.$content.' |'.PHP_EOL.$line.PHP_EOL;
    }

    public function parseHr(): string
    {
        return str_repeat('-', (int) exec('tput cols')); // TODO size
    }

    public function parseB(DOMNode $node): string
    {
        return "\033[1m".$this->parse($node);
    }

    public function parseI(DOMNode $node): string
    {
        return "\033[3m".$this->parse($node);
    }

    public function parseU(DOMNode $node): string
    {
        return "\033[4m".$this->parse($node);
    }

    public static function lenght(string $target): int
    {
        return strlen(preg_replace("/\033\[[0-9]+m/", '', $target));
    }

    public static function removeWhitespace(DOMText $element): string
    {
        $content = $element->textContent;
        if ($element->previousSibling === null) {
            $content = ltrim($content);
        }

        if ($element->nextSibling === null) {
            $content = rtrim($content);
        }

        return preg_replace("/(\n|\r)/", '', preg_replace('/\s{2,}/', ' ', $content));
    }
}
