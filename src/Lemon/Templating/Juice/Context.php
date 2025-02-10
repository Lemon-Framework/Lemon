<?php

namespace Lemon\Templating\Juice;

enum Context
{
    case Html;
    case HtmlTag;
    case HtmlString;
    case JuiceString;
    case Juice;
    /**
     * Inside juice block which can't be ended (due to e.g unclosed bracket)
     * Useful for directive ending token that is already used bracket
     *
     * ACTUALY WE CAN GET RID OF DIS AND JUST DONT PARSE IT WHILE IN JUICE CONTEXT BCS WE DONT CARE WHEN IT ENDS UNLESS DER IS ERROR AHA
     * zatim nemazat kdyby nahodou
     */
    case JuiceUnclosed;
}
