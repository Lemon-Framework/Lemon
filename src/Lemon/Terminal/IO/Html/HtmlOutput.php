<?php

declare(strict_types=1);

namespace Lemon\Terminal\IO\Html;

use DOMDocument;
use DOMNode;
use DOMXPath;

class HtmlOutput
{
    private Components $components;

    public function __construct()
    {
        $this->components = new Components();
    }

    public function compile(string $content): string
    {
        return $this->components->parse($this->getDom($content));
    }

    private function getDom(string $content): DOMNode
    {
        $dom = new DOMDocument();
        $dom->loadHTML($content);
        $xpath = new DOMXPath($dom);

        return $xpath->document->getElementsByTagName('body')[0];
    }
}
