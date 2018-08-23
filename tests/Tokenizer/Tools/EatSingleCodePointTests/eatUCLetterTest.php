<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\EatSingleCodePointTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSASTTests\Tokenizer\Tools\eatSingleCodePointTest;
use function Netmosfera\PHPCSSASTDev\Sets\getUCLetterCodePointSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatUCLetter;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatUCLetterTest extends eatSingleCodePointTest
{
    function getExpectedCodePointSet(): CompressedCodePointSet{
        return getUCLetterCodePointSet();
    }

    function getEatFunction(): Closure{
        return function(...$args){ return eatUCLetter(...$args); };
    }
}
