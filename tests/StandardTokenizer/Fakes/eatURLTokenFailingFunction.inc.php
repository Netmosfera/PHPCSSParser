<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use PHPUnit\Framework\TestCase;

function eatURLTokenFailingFunction(){
    return function(Traverser $traverser): ?URLToken{
        TestCase::fail();
    };
}
