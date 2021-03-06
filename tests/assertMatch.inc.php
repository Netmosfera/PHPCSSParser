<?php

namespace Netmosfera\PHPCSSASTTests;

use function Netmosfera\PHPCSSAST\match;
use PHPUnit\Framework\TestCase;

function assertMatch($a, $b){
    TestCase::assertTrue(match($a, $a));
    TestCase::assertTrue(match($b, $b));
    TestCase::assertTrue(match($a, $b));
}
