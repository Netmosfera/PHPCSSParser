<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTTests\Tokenizer\getSeqOfAnyCodePoint;
use function Netmosfera\PHPCSSASTTests\Tokenizer\getPrefixes;
use function Netmosfera\PHPCSSASTDev\Sets\getWhitespaceCodePointSet;
use function Netmosfera\PHPCSSASTDev\Sets\getWhitespaceSequencesSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatWhitespace;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatWhitespaceTest extends TestCase
{
    function data_returns_TRUE_if_the_next_code_point_is_a_whitespace(){
        return cartesianProduct(getPrefixes(), getWhitespaceSequencesSet(), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_TRUE_if_the_next_code_point_is_a_whitespace */
    function test_returns_TRUE_if_the_next_code_point_is_a_whitespace(String $prefix, String $whitespace, String $rest){
        $t = new Traverser($prefix . $whitespace . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespace($t), $whitespace));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_not_a_whitespace(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getWhitespaceCodePointSet());
        return cartesianProduct(getPrefixes(), getCodePointsFromRanges($codePoints), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_not_a_whitespace */
    function test_returns_FALSE_if_the_next_code_point_is_not_a_whitespace(String $prefix, String $nonWhitespace, String $rest){
        $t = new Traverser($prefix . $nonWhitespace . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespace($t), NULL));
        self::assertTrue(match($t->eatAll(), $nonWhitespace . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(getPrefixes());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatWhitespace($t), NULL));
        self::assertTrue(match($t->eatAll(), ""));
    }
}
