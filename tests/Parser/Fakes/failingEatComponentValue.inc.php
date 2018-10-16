<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Fakes;

use Netmosfera\PHPCSSAST\Nodes\ComponentValueNode;
use Netmosfera\PHPCSSAST\Parser\TokenStream;
use PHPUnit\Framework\TestCase;

function failingEatComponentValue(){
    return function(TokenStream $stream): ?ComponentValueNode{
        TestCase::fail();
    };
}
