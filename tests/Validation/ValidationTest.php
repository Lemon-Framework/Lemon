<?php

declare(strict_types=1);

namespace Lemon\Tests\Validation;

use Lemon\Tests\TestCase;
use Lemon\Validation\Validator;

class ValidationTest extends TestCase
{
    public function testRuleParsing()
    {
        $validator = new Validator();
        $this->assertSame([['foo'], ['bar', 10]], $validator->resolveRules([['foo'], ['bar', 10]]));
        $this->assertSame([['foo'], ['bar', '10']], $validator->resolveRules('foo|bar:10'));
    }

    public function testValidation()
    {
        $validator = new Validator();
        $this->assertTrue($validator->validate(
            ['foo' => '10',],
            ['foo' => 'numeric',]
        ));

        $this->assertTrue($validator->validate([], [
            'foo' => 'optional',
        ]));

        $this->assertTrue($validator->validate(
            ['foo' => '10'], 
            ['foo' => 'optional|numeric',]
        ));

        $this->assertFalse($validator->validate(
            [],
            ['foo' => 'numeric',]
        ));

        $this->assertFalse($validator->validate(
            ['foo' => 'parek',],
            ['foo' => 'max:1',]
        ));
    }
}
