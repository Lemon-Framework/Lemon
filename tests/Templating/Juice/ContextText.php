<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Parser;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ContextText extends TestCase
{
    public function testScriptResolving()
    {
        $this->assertSame(Parser::CONTEXT_JS, Parser::resolveContext(<<<'HTML'
            <script>
                alert('ok');
            </script>
            <div>
                <script src="ok"></script>
            </div>
            <script something>>>
            foo=
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_JS, Parser::resolveContext('foo=', Parser::CONTEXT_JS));

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(<<<'HTML'
            alert('klobna')
            </script>
            <div>neco
                <script idk>>>
                    ok
                </script>
            </div>
        HTML, Parser::CONTEXT_JS));
    }
}
