<?php

arch('this should have been added to an arch group', function () {
    expect(test()->groups()[0])->toBe('arch');
});
