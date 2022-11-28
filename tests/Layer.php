<?php

use Tests\Fixtures\Support\Collection;

it('does not include native classes', function () {
    expect(Collection::class)->toDependOnNothing();
});
