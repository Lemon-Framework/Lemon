<?php

declare(strict_types=1);

namespace Lemon\Tests\Validation;

use Lemon\Contracts\Translating\Translator;
use Lemon\Tests\TestCase;
use Lemon\Validation\Validator;

/**
 * @internal
 *
 * @coversNothing
 */
class ValidationTest extends TestCase
{
    public function testRuleParsing()
    {
        $mock = \Mockery::mock(Translator::class);
        $validator = new Validator($mock);
        $this->assertSame([['foo'], ['bar', 10]], $validator->resolveRules([['foo'], ['bar', 10]]));
        $this->assertSame([['foo'], ['bar', '10']], $validator->resolveRules('foo|bar:10'));
    }

    public function testValidation()
    {
        $mock = \Mockery::mock(Translator::class);
        $mock->shouldReceive('text')
            ->andReturnUsing(fn ($x) => match ($x) {
                'validation.numeric' => '%field must be numeric',
                'validation.missing' => '%field is missing',
                'validation.max' => '%field is longer than %arg',
            })
        ;
        $validator = new Validator($mock);
        $this->assertTrue($validator->validate(
            ['foo' => '10'],
            ['foo' => 'numeric']
        ));

        $this->assertTrue($validator->validate([], [
            'foo' => 'optional',
        ]));

        $this->assertTrue($validator->validate(
            ['foo' => '10'],
            ['foo' => 'optional|numeric']
        ));

        $this->assertTrue($validator->validate(
            ['foo' => 10],
            ['foo' => 'optional|numeric']
        ));

        $this->assertFalse($validator->validate(
            [],
            ['foo' => 'numeric']
        ));
        $this->assertSame('foo is missing', $validator->error());

        $this->assertFalse($validator->validate(
            ['foo' => ''],
            ['foo' => 'numeric']
        ));
        $this->assertSame('foo is missing', $validator->error());

        $this->assertFalse($validator->validate(
            ['foo' => 'parek'],
            ['foo' => 'max:1']
        ));
        $this->assertSame('foo is longer than 1', $validator->error());

        $this->assertTrue($validator->validate(
            ['foo' => '0'],
            ['foo' => 'numeric'],
        ));

        $this->assertFalse($validator->validate(
            ['foo' => 'parek'],
            ['foo' => 'numeric'],
        ));
        $this->assertSame('foo must be numeric', $validator->error());
    }
}
