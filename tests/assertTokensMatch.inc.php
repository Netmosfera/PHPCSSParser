<?php

namespace Netmosfera\PHPCSSASTTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Token;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function assertTokensMatch(Token $a, Token $b){
    TestCase::assertTrue($a->equalsExactly($b));
}
