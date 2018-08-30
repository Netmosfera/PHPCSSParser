<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameCodePoint;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNameCodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNameItemsSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNameCodePoint(...$args); };
    }
}
