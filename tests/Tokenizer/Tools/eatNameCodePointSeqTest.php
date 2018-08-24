<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNameCodePointSeq;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNameCodePointSeqTest extends TestCase
{
    // @TODO improve these tests
    function test(){
        $prefix = "sdfij";
        $rest = "@sepa";
        $name = "abc-def\u{2764}123";
        $t = new Traverser($prefix . $name . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNameCodePointSeq($t), $name));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
