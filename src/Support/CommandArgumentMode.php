<?php

namespace Pest\Arch\Support;

enum CommandArgumentMode
{
    case AllTests;
    case OnlyArchTests;
    case ExceptArchTests;
}
