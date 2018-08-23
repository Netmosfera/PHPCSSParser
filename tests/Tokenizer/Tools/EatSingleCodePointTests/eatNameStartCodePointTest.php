<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\EatSingleCodePointTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTTests\Tokenizer\Tools\eatSingleCodePointTest;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameStartCodePoint;
use function Netmosfera\PHPCSSASTDev\Sets\getNameStartCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNameStartCodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNameStartCodePointSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNameStartCodePoint(...$args); };
    }
}
