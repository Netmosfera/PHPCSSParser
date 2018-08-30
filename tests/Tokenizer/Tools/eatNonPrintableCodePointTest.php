<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNonPrintableCodePoint;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNonPrintablesSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNonPrintableCodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNonPrintablesSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNonPrintableCodePoint(...$args); };
    }
}
