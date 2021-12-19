<?php

use Lemon\Support\Types\Array_;
use Lemon\Support\Types\Arr;

test("Static call", function() {
    expect(Arr::hasKey(["foo" => "bar"], "foo"))->toBe(true);
});

test("Array from json", function() {
    expect(Array_::fromJson("{\"foo\": \"bar\"}"))->toBe(["foo" => "bar"]);
});

test("Array to json", function() {
    $array = new Array_(["foo"]);
    expect($array->json())->toBe("[\"foo\"]");
});

test("Array export", function() {
    $array = new Array_(["foo", new Array_(["bar", new Array_(["baz"])])]);
    expect($array->export())->toBe(["foo", ["bar", ["baz"]]]);
});

test("Array get", function() {
    $array = new Array_(["foo"=>"bar"]);  
    expect($array->foo)->toBe("bar");
});

test("Array set", function() {
    $array = new Array_();
    $array->foo = "bar";
    expect($array->content)->toBe(["foo" => "bar"]);
});

test("Array lenght", function() {
    $array = new Array_(["foo", "bar"]);
    expect($array->lenght())->toBe(2);
});

test("Array chunk", function() {
    $array = new Array_([1, 2, 3, 4]);
    $array->chunk(2);
    expect($array->content)->toBe([[1, 2], [3, 4]]);
});

test("Array key exist", function() {
    $array = new Array_([0]);
    expect($array->hasKey(1))->toBe(false);
    expect($array->hasKey(0))->toBe(true);
});

test("Array filter", function() {
    $array = new Array_([["foo", "bar"], ["bar", "baz"], ["foo", "baz"]]);
    $array->filter(function($item) {
        return $item[0] === "foo";
    });

    expect($array->content)->toBe([0 => ["foo", "bar"], 2 => ["foo", "baz"]]);
});

test("Array first key", function() {
    $array = new Array_([1, 2, 3]); 
    expect($array->firstKey())->toBe(0);
});

test("Array last key", function() {
    $array = new Array_([1, 2, 3]); 
    expect($array->lastKey())->toBe(2);
});


test("Array first value", function() {
    $array = new Array_([1, 2, 3]); 
    expect($array->first())->toBe(1);
});

test("Array last value", function() {
    $array = new Array_([1, 2, 3]); 
    expect($array->last())->toBe(3);
});

test("Array keys", function() {
    $array = new Array_(["foo" => "bar"]);
    expect($array->keys()->content)->toBe(["foo"]);
});

test("Array values", function() {
    $array = new Array_(["foo" => "bar"]);
    expect($array->values()->content)->toBe(["bar"]);
});

test("Array map", function() {
    $array = new Array_(["foo", "bar", "baz"]);
    $array->map(function($item) {
        return $item . "o";
    });

    expect($array->content)->toBe(["fooo", "baro", "bazo"]);
});

test("Array merge", function() {
    $array1 = new Array_(["foo", "bar"]);
    $array2 = new Array_(["baz", 1]);
    $array3 = [2, 3];
    expect($array1->merge($array2, $array3)->content)->toBe(["foo", "bar", "baz", 1, 2, 3]);
});

test("Array replace", function() {
    $array1 = new Array_(["foo", "bar"]);
    $array2 = new Array_([0 => "baz"]);
    expect($array1->replace($array2)->content)->toBe(["baz", "bar"]);
});

test("Array reverse", function() {
    $array = new Array_(["foo", "bar", "baz"]);
    expect($array->reverse()->content)->toBe(["baz", "bar", "foo"]);
});

test("Array slice", function() {
    $array = new Array_([1, 2, 3, 4]);
    expect($array->slice(1, 2)->content)->toBe([2, 3]);
});

test("Array sum", function() {
    $array = new Array_([1, 2, 3]);
    expect($array->sum())->toBe(6);
});

test("Array contains", function() {
    $array = new Array_([1, 2, 3]);
    expect($array->contains(2))->toBe(true);
    expect($array->contains(4))->toBe(false); 
});
