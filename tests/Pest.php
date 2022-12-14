<?php

uses()->beforeEach(function () {
    $this->arch()->ignore([
        'NunoMaduro\Collision',
    ]);
})->in(__DIR__);
