<?php

namespace Lemon\Tests\Exceptions\Handling;

use Lemon\Debug\Handling\Consultant;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConsultantTest extends TestCase
{
    public function testRegularExpressions()
    {
        $consultant = new Consultant('Call to undefined function foo()');
        $this->assertSame('function', $consultant->findHandler());

        $consultant = new Consultant('Call to undefined method Foo::bar()');
        $this->assertSame('method', $consultant->findHandler());

        $consultant = new Consultant('Undefined property: Foo::$bar');
        $this->assertSame('property', $consultant->findHandler());

        $consultant = new Consultant('Unexpected <?php at line 37');
        $this->assertSame('viewPHPTags', $consultant->findHandler());

        $consultant = new Consultant('Unexpected <?= at line 37');
        $this->assertSame('viewPHPTags', $consultant->findHandler());

        $consultant = new Consultant('View bramboraky.foo does not exist or is not readable');
        $this->assertSame('wrongViewName', $consultant->findHandler());
    }

    public function testFindBestMatch()
    {
        $haystack = [
            'kulovy_fid',
            'mrkvovy_dort',
            'parkovar',
            'fid_kulovy',
        ];
        $consultant = new Consultant('');
        $this->assertSame('kulovy_fid', $consultant->bestMatch($haystack, 'kylovyfid'));
        $this->assertSame('kulovy_fid', $consultant->bestMatch($haystack, 'kulovyFider'));
        $this->assertSame('kulovy_fid', $consultant->bestMatch($haystack, 'kylovy'));
    }

    /*
     * public function testUndefinedFunctionHandler()
     * {
     * $consultant = new Consultant('');
     *
     * $this->assertSame(['Did you mean strpos?'], $consultant->handleFunction([
     * '',
     * 'strpso'
     * ]));
     * }*/
}
