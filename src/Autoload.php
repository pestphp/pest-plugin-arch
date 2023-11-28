<?php

declare(strict_types=1);

use Pest\Arch\Concerns\Architectable;
use Pest\Concerns\Expectable;
use Pest\PendingCalls\TestCall;
use Pest\Plugin;
use PHPUnit\Framework\TestCase;

Plugin::uses(Architectable::class);

if (! function_exists('arch')) {
    /**
     * Adds the given closure as an architecture test. The first
     * argument is the test description; the second argument
     * is a closure that contains the test expectations.
     *
     * @return Expectable|TestCall|TestCase|mixed
     */
    function arch(string $description, Closure $closure = null): TestCall
    {
        return test($description, $closure)->group('arch');
    }
}
