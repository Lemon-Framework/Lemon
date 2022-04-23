<?php

declare(strict_types=1);

namespace Lemon\Tests\Debug;

use Lemon\Debug\Style;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class StyleTest extends TestCase
{
    public function testGenerating()
    {
        $s = new Style();
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

            HTML, $s->generate());
    }
}
