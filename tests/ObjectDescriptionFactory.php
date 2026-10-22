<?php

use Pest\Arch\Factories\ObjectDescriptionFactory;

it('does not keep the syntax tree of a described object', function () {
    $object = ObjectDescriptionFactory::make(__DIR__.'/Fixtures/Misc/HasNativeFunctions.php');

    expect($object->name)->toBe('Tests\Fixtures\Misc\HasNativeFunctions')
        ->and($object->uses)->not->toBeEmpty()
        ->and($object->stmts)->toBe([]);
});
