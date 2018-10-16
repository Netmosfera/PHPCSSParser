<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Fakes;

use Netmosfera\PHPCSSAST\Tokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use PHPUnit\Framework\TestCase;

function eatEscapeTokenFailingFunction(){
    return function(Traverser $traverser): ?EscapeToken{
        TestCase::fail();
    };
}
