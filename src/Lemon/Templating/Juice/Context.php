<?php

namespace Lemon\Templating\Juice;

enum Context
{
    case Html;
    case HtmlTag;
    case Juice;
    /**
     * Inside juice block which can't be ended (due to e.g unclosed bracket)
     * Useful for directive ending token that is already used bracket
     */
    case JuiceUnclosed;
}
