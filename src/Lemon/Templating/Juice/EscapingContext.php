<?php

namespace Lemon\Templating\Juice;

enum EscapingContext
{
    // tbd
    case Html;
    case Attribute;
    case Script;
    case ScriptAttribute;

    public function getEscapingMethod(): string 
    {
        // todo
        return 'escape'.match($this) {
            self::Html => 'Html',
            self::Attribute => 'Attribute',
            self::ScriptAttribute => 'Attribute',
            self::Script => 'Script',
        };
    }
}
