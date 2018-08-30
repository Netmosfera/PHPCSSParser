<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameStartCodePoint;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNameStartersSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNameStartCodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNameStartersSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNameStartCodePoint(...$args); };
    }
}
