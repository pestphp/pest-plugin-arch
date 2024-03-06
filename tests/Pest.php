<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Pest\Arch\Expectations\Targeted;
use Pest\Arch\Objects\ObjectDescription;

uses()->beforeEach(function () {
    $this->arch()->ignore([
        'NunoMaduro\Collision',
    ]);
})->in(__DIR__);

expect()->extend('toThrowArchitectureViolation', function (string $message, string $file, int $line) {
    return $this->toThrow(function (ArchExpectationFailedException $exception) use ($line, $file, $message) {
        $frame = $exception->toCollisionEditor();
        $violationFile = str_replace(DIRECTORY_SEPARATOR, '/', $frame->getFile());
        $violationLine = $frame->getLine();

        expect($exception->getMessage())->toBe($message)
            ->and($violationFile)
            ->toEndWith($file)
            ->not->toContain('/vendor/composer/../..')
            ->not->toContain('\vendor\composer\..\..')
            ->and($violationLine)
            ->toBe($line);
    });
});

expect()->extend('getTargets', function () {
    $classes = [];
    Targeted::make(
        $this,
        function (ObjectDescription $object) use (&$classes): bool {
            $classes[] = $object->name;

            return true;
        },
        '',
        fn ($path) => 0,
    );
    $this->value = $classes;

    return $this;
});
