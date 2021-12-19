<?php

use Lemon\Support\Types\Str;
use Lemon\Support\Types\String_;

test("Static string methods", function() {
    expect(Str::split("foo=bar", "=")->content)->toBe(["foo", "bar"]);
    expect(function() { Str::from("foo"); })->toThrow(Exception::class);
});

test("String split", function() {
    expect(String_::from("foo=bar")->split(String_::from("="))->content)->toBe(["foo", "bar"]);
});

test("String join", function() {
    expect(String_::from("=")->join(["foo", "bar"])->content)->toBe("foo=bar");
});

test("String capitalize", function() {
    expect(String_::from("foo")->capitalize()->content)->toBe("Foo");
});

test("String decapitalize", function() {
    expect(String_::from("FOO")->decapitalize()->content)->toBe("fOO");
});

test("String contains", function() {
    expect(String_::from("foobar")->contains(String_::from("ob")))->toBe(true);
    expect(String_::from("foobar")->contains(String_::from("baz")))->toBe(false);
});

test("String starts with", function() {
    expect(String_::from("foobar")->startsWith("foo"))->toBe(true);
    expect(String_::from("foobar")->startsWith("bar"))->toBe(false);
});

test("String ends with", function() {
    expect(String_::from("barbar")->endsWith("bar"))->toBe(true);
    expect(String_::from("foobar")->endsWith("foo"))->toBe(false);
});

test("String replace", function() {
    expect(String_::from("foobar")->replace("foo", "baz")->content)->toBe("bazbar");
});

test("String shuffle", function() {
    expect(String_::from("foobar")->shuffle()->content)->not->toBe("foobar");
});

test("String reverse", function() {
    expect(String_::from("foobar")->reverse()->content)->toBe("raboof");
});
