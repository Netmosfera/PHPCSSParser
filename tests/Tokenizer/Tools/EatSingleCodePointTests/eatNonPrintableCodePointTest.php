<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\EatSingleCodePointTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTTests\Tokenizer\Tools\eatSingleCodePointTest;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNonPrintableCodePoint;
use function Netmosfera\PHPCSSASTDev\Sets\getNonPrintableCodePointSet;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNonPrintableCodePointTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getNonPrintableCodePointSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatNonPrintableCodePoint(...$args); };
    }
}
