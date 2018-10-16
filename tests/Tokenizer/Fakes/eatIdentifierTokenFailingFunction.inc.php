<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokenizer\Traverser;
use PHPUnit\Framework\TestCase;

function eatIdentifierTokenFailingFunction(){
    return function(Traverser $traverser): ?IdentifierToken{
        TestCase::fail();
    };
}
