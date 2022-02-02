<?php

namespace Lemon\Terminal;

use DOMDocument;
use DOMText;
use DOMXPath;

class Output
{

    private Terminal $terminal;

    private string $content;

    const CLASSES = []; // WIP

    public function __construct(Terminal $terminal, string $content)
    {
        $this->terminal = $terminal;
        $this->content = $content;
    }

    public function parseHeading($heading)
    {
        $line = str_repeat('-', strlen($heading) + 2);
        return 
            '+' .       $line .      '+' . PHP_EOL . 
            '| ' . trim($heading) . ' |' . PHP_EOL .
            '+' .       $line .      '+' . PHP_EOL;
    }

    public function parseClasses($node)
    {
        $result = ['', ''];
        if ($node instanceof DOMText)
            return $result;

        if (!($classes = $node->attributes->getNamedItem('class')))
            return $result;

        foreach (explode(' ', $classes->value) as $class_name)
        {
            $style = self::CLASSES[$class_name];
            $result[0] .= $style[0];
            $result[1] = $style[1] . $result[1];
        }

        return $result;
    }

    public function parseNode($node)
    {
        $classes = $this->parseClasses($node);
        return 
            $classes[0] .
            match ($node->nodeName)
            {
                'h1'=>
                    $this->parseHeading($node->textContent),
                'hr' =>
                    PHP_EOL . str_repeat('—', $this->terminal->width()) . PHP_EOL,
                default =>
                    trim($node->textContent)
            }
            . $classes[1];
    }

    public function parse()
    {
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);
        $xpath = new DOMXPath($dom);
        $result = '';

        foreach ($xpath->document->getElementsByTagName('body')[0]->childNodes as $node)
            $result .= $this->parseNode($node);

        return $result;
    }

    public function render()
    {

    }
}
