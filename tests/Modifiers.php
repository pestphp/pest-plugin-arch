<?php

use Tests\Fixtures\Contracts\Controllers\Indexable;
use Tests\Fixtures\Controller;
use Tests\Fixtures\HasResponses;

test('only classes extending Controller are tested', function (): void {
    expect('Tests\Fixtures\Controllers')
        ->extending(Controller::class)
        ->toExtend(Controller::class);
});

test('only classes implementing interfaces are tested', function (): void {
    expect('Tests\Fixtures\Controllers')
        ->implementing(Indexable::class)
        ->toImplement(Indexable::class);
});

test('only class using traits are tested', function (): void {
    expect('Tests\Fixtures\Controllers')
        ->using(HasResponses::class)
        ->toUseTrait(HasResponses::class);
});

test('only abstract classes are tested', function (): void {
    expect('Tests\Fixtures\Misc\Abstracts')
        ->abstracts()
        ->toHaveMethod('edible');
});
