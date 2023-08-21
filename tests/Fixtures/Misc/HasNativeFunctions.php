<?php

namespace Tests\Fixtures\Misc;

class HasNativeFunctions
{
    public function startSleeping(): void
    {
        sleep(1);
    }

    public function dieWithStatus(int $status): void
    {
        die($status);
    }

    public function exitWithStatus(int $status): void
    {
        exit($status);
    }

    public function evaluateCode(string $code): void
    {
        eval($code);
    }

    public function makeAClone(object $object): object
    {
        return clone $object;
    }

    public function checkArray(array $array): bool
    {
        if(empty($array)){
            return false;
        }

        if(!isset($array[1])){
            return false;
        }

        return true;
    }

    public function printSomething(string $text): void
    {
        print $text;
    }
}
