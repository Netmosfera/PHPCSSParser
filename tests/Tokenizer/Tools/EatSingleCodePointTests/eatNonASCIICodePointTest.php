<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\EatSingleCodePointTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTTests\Tokenizer\Tools\eatSingleCodePointTest;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNonASCIICodePoint;
use function Netmosfera\PHPCSSASTDev\Sets\getNonASCIICodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNonASCIICodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNonASCIICodePointSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNonASCIICodePoint(...$args); };
    }
}
