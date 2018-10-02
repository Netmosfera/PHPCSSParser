<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use PHPUnit\Framework\TestCase;

function eatIdentifierTokenFailingFunction(){
    return function(Traverser $traverser): ?IdentifierToken{
        TestCase::fail();
    };
}
