<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Parser;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ContextTest extends TestCase
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

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(<<<'HTML'
                <div>neco
                    <script>
                        ok
                    </script>
                </div>
            HTML, Parser::CONTEXT_HTML));
    }

    public function testJsAttributeResolving()
    {
        $this->assertSame(Parser::CONTEXT_JS_ATTRIBUTE, Parser::resolveContext(<<<'HTML'
                <div>parek</div>
               <div><div onclick='alert("
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_JS_ATTRIBUTE, Parser::resolveContext(<<<'HTML'
                <script></script>
                <div onclick="alert(\"
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(<<<'HTML'
                onclick="alert(\"
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(')"', Parser::CONTEXT_JS_ATTRIBUTE));
    }

    public function testAttributeResolving()
    {
        $this->assertSame(Parser::CONTEXT_ATTRIBUTE, Parser::resolveContext(<<<'HTML'
                <script></script>
                <div><div></div></div>
                <script src='
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_ATTRIBUTE, Parser::resolveContext(<<<'HTML'
                <script></script>
                <div><div><div><script></script></div></div>
                <link href="
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(<<<'HTML'
                <script></script>
                <div><div><div><script></script></div></div>
                <link rel="
            HTML, Parser::CONTEXT_HTML));

        $this->assertSame(Parser::CONTEXT_HTML, Parser::resolveContext(')"', Parser::CONTEXT_JS_ATTRIBUTE));

    }
}
