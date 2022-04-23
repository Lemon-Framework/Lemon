<?php

namespace Lemon\Tests\Debug;

use Lemon\Config\Config;
use Lemon\Debug\Dumper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DumperTest extends TestCase
{
    public function getDumper()
    {
        return new Dumper(new Config());
    }

    public function testNumericParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-number">-10.5</span>', $dumper->resolve(-10.5));
    }

    public function testBoolParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-bool">true</span>', $dumper->resolve(true));
        $this->assertSame('<span class="ldg-bool">false</span>', $dumper->resolve(false));
    }

    public function testStringParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<span class="ldg-string">"bramboraky"</span>', $dumper->resolve('bramboraky'));
    }

    public function testIteratorParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[<span class="ldg-number">0</span>]</span> => <span class="ldg-number">10</span></span></details>]', $dumper->resolve([10]));

        // I apologize to eveyone who will try to read it
        $this->assertSame('<details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[<span class="ldg-number">0</span>]</span> => <span class="ldg-number">10</span></span><span class="ldg-array-item"><span class="ldg-array-key">[<span class="ldg-number">1</span>]</span> => <details><summary>array [</summary><span class="ldg-array-item"><span class="ldg-array-key">[<span class="ldg-string">"foo"</span>]</span> => <span class="ldg-string">"lisky"</span></span></details>]</span></details>]', $dumper->resolve([10, ['foo' => 'lisky']]));
    }

    public function testObjectParsing()
    {
        $dumper = $this->getDumper();
        $this->assertSame('<details><summary>'.Foo::class.' [</summary><span class="ldg-property"><span class="ldg-property-name">bar</span> => <span class="ldg-string">"foo"</span></span><span class="ldg-property"><span class="ldg-property-name">baz</span> => <span class="ldg-null">null</span></span></details>]', $dumper->resolve(new Foo()));
    }

    public function testDumping()
    {
        $dumper = $this->getDumper();
        $this->assertSame(<<<'HTML'
            <style>
                .ldg {
                    background-color: #282828;
                    color: #ebdbb2;
                }
        
                .ldg-array-item .ldg-property {
                    margin-left: 1%;
                }
        
                .ldg-array-key {
                    color: #d79921
                }
        
                .ldg-property-name {
                    color: #458588
                }
        
                .ldg-string {
                    color: #98971a
                }
        
                .ldg-number {
                    color: #b16286
                }
        
                .ldg-bool {
                    color: #b16286
                }
        
                .ldg-null {
                    color: #d79921
                }
        
            </style>
            <div class="ldg"><span class="ldg-string">"foo"</span></div>
            HTML, $dumper->dump('foo'));

        $this->assertSame('<div class="ldg"><span class="ldg-string">"foo"</span></div>', $dumper->dump('foo'));
    }
}

class Foo
{
    public string $bar = 'foo';

    public float $baz;

    private bool $buz;
}
