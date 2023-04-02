<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;

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
