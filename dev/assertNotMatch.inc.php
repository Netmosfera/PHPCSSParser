<?php

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function assertNotMatch($a, $b){
    TestCase::assertFalse(match($a, $b));
}
