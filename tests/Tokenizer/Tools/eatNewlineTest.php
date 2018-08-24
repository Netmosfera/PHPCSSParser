<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNewline;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNewlineTest extends TestCase
{
    function data_returns_TRUE_if_the_next_code_point_is_a_newline(){
        return cartesianProduct(
            ANY_UTF8(),
            getNewlineSeqsSet(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_TRUE_if_the_next_code_point_is_a_newline */
    function test_returns_TRUE_if_the_next_code_point_is_a_newline(String $prefix, String $newline, String $rest){
        $t = new Traverser($prefix . $newline . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNewline($t), $newline));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_not_a_newline(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getNewlinesSet());
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges($codePoints),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_not_a_newline */
    function test_returns_FALSE_if_the_next_code_point_is_not_a_newline(String $prefix, String $nonNewline, String $rest){
        $t = new Traverser($prefix . $nonNewline . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNewline($t), NULL));
        self::assertTrue(match($t->eatAll(), $nonNewline . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNewline($t), NULL));
        self::assertTrue(match($t->eatAll(), ""));
    }
}
