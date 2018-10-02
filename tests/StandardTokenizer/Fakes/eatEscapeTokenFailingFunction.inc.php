<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes;

use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use PHPUnit\Framework\TestCase;

function eatEscapeTokenFailingFunction(){
    return function(Traverser $traverser): ?EscapeToken{
        TestCase::fail();
    };
}
