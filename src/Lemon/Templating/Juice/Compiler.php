<?php

namespace Lemon\Views\Juice;

use Lemon\Exceptions\ViewException;

class Compiler
{
    public function __construct($template, $name)
    {
        $this->template = $template;
        $this->name = $name;
        $this->tags = [
            new Tag("{{\s*(.*?)\s*}}", ["<?= htmlentities(",") ?>"]),
            new Tag("{!\s*(.*?)\s*!}", ["<?= ", " ?>"]),
            new Tag("{-(.*?)-}", ["", ""]) 
        ];

        /*$this->directives = [
            ["{%\s*if\s*(.*?)\s*%}", "{%\s*endif\s*%}"] => ["<?php if (", "): ?>", "<?php endif; ?>"]
        ];*/
    }

    public function compile()
    {
        $template = $this->template;
        if (preg_match("/<\?(php|=)/", $template, $matches, PREG_OFFSET_CAPTURE) == 1)
            throw new ViewException("Unexpected <?{$matches[1][0]} at line {$matches[1][1]}"); // TODO line counting
        foreach ($this->tags as $tag)
            $template = $tag->compile($template); 
        return $template;
    }
}
