<?php

namespace Lemon\Terminal;

use DOMDocument;
use DOMNode;
use DOMText;
use DOMXPath;

class Output
{

    private Terminal $terminal;

    private string $content;

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
        $result = [null, null];
        if ($node instanceof DOMText)
            return $result;

        if (!($classes = $node->attributes->getNamedItem('class')))
            return $result;

        $styles = $this->terminal->getStyles();

        foreach (explode(' ', $classes->value) as $class_name)
        {
            $style = $styles->resolveClass($class_name);
            $result[0] .= $style[0];
            $result[1] = $style[1] . $result[1];
        }

        return $result;
    }


    public function getParrentStyles(DOMNode|DOMText $node)
    {
        return $this->parseClasses($node->parentNode)[0] ?? "\033[39m";
    }

    public function parseNode($node)
    {
        $classes = $this->parseClasses($node);

        $close = 
            $classes[1] == '<PARENT>' 
            ? $this->getParrentStyles($node) 
            : $classes[1];
        
        return
            $classes[0] .
            match ($node->nodeName)
            {
                'h1'=>
                    $this->parseHeading($node->textContent),
                'hr' =>
                    PHP_EOL . str_repeat('—', $this->terminal->width()) . PHP_EOL,
                'b', 'strong' =>
                    "\033[1m" . $node->textContent . "\033[39m",
                'i' =>
                    "\033[3m" . $node->textContent . "\033[39m",
                'u' =>
                    "\033[4m" . $node->textContent . "\033[39m", 
                'div' => $this->parseNodes($node->childNodes),
                default =>
                    $node->textContent            
            }
            . $close;
    }

    public function parseNodes($nodes)
    {
        $result = '';
        foreach ($nodes as $node)
            $result .= $this->parseNode($node);

        return $result;
    }

    public function parse()
    {
        $dom = new DOMDocument();
        $dom->loadHTML($this->content);
        $xpath = new DOMXPath($dom);

        return $this->parseNodes($xpath->document->getElementsByTagName('body')[0]->childNodes);

    }


}
