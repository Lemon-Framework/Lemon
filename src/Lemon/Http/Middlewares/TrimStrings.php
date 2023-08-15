<?php

declare(strict_types=1);

namespace Lemon\Http\Middlewares;

use Lemon\Http\Request;
use Lemon\Tests\TestCase;

class TrimStrings extends TestCase
{
    public function handle(Request $request)
    {
        $request->replace(array_map(trim(...), $request->data()));
        $request->replaceQuery(array_map(fn($item) => trim($item, ' \\t\\n\\r\\0\\x0B'.chr(32)), $request->query() ?? []));
    }
}
