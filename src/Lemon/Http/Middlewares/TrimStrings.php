<?php

declare(strict_types=1);

namespace Lemon\Http\Middlewares;

use Lemon\Http\Request;

class TrimStrings
{
    public function handle(Request $request)
    {
        $request->replace(array_map(trim(...), $request->data()));
        $request->replaceQuery(array_map(trim(...), $request->query() ?? []));
    }
}
