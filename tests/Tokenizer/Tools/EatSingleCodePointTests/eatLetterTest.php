<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\EatSingleCodePointTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTTests\Tokenizer\Tools\eatSingleCodePointTest;
use function Netmosfera\PHPCSSASTDev\Sets\getLetterCodePointSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatLetter;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatLetterTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getLetterCodePointSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatLetter(...$args); };
    }
}
