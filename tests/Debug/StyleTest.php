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
                @import url('https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@300&display=swap');
                .ldg {
                    font-family: 'Source Code Pro', monospace;
                    background-color: #282828;
                    color: #ebdbb2;
                    padding: 1%;
                }

                .ldg-array-item, .ldg-property {
                    margin-left: 1%;
                    display: flex;
                }

                .ldg-array-value > details > .ldg-array-item {
                    margin-left: 15%;
                }

                details > summary {
                    list-style-type: none;
                }
               
                .ldg-array-key {
                    color: #d79921;
                    margin-right: 0.5%;
                }

                .ldg-array-value {
                    margin-left: 0.5%;
                }

                .ldg-property-name {
                    color: #458588;
                }

                .ldg-string {
                    color: #98971a;
                }

                .ldg-number {
                    color: #b16286;
                }

                .ldg-bool {
                    color: #b16286;
                }

                .ldg-null {
                    color: #d79921;
                }

            </style>

            HTML, $s->generate());
    }
}
