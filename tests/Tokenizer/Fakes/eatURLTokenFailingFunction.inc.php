<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Netmosfera\PHPCSSAST\Tokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use PHPUnit\Framework\TestCase;

function eatURLTokenFailingFunction(){
    return function(Traverser $traverser): ?URLToken{
        TestCase::fail();
    };
}
