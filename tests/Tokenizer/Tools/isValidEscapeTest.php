<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_STRING;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isValidEscape;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\cp;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class isValidEscapeTest extends TestCase
{
    function data_returns_FALSE_if_the_next_code_point_is_not_a_backslash(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("\\"));
        return cartesianProduct(
            ANY_STRING(),
            getCodePointsFromRanges($codePoints),
            ANY_STRING()
        );
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_not_a_backslash */
    function test_returns_FALSE_if_the_next_code_point_is_not_a_backslash(String $prefix, String $noBackslashCodePoint, String $rest){
        $t = new Traverser($prefix . $noBackslashCodePoint . "a" . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isValidEscape($t), FALSE));
        self::assertTrue(match($t->eatAll(), $noBackslashCodePoint . "a" . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(
            ANY_STRING()
        );
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isValidEscape($t), FALSE));
        self::assertTrue(match($t->eatAll(), ""));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_backslash_is_followed_by_newline(){
        return cartesianProduct(
            ANY_STRING(),
            getNewlineSeqsSet(),
            ANY_STRING()
        );
    }

    /** @dataProvider data_returns_FALSE_if_backslash_is_followed_by_newline */
    function test_returns_FALSE_if_backslash_is_followed_by_newline(String $prefix, String $invalidEscape, String $rest){
        $t = new Traverser($prefix . "\\" . $invalidEscape . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isValidEscape($t), FALSE));
        self::assertTrue(match($t->eatAll(), "\\" . $invalidEscape . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_backslash_is_followed_by_EOF(){
        return cartesianProduct(
            ANY_STRING()
        );
    }

    /** @dataProvider data_returns_FALSE_if_backslash_is_followed_by_EOF */
    function test_returns_FALSE_if_backslash_is_followed_by_EOF(String $prefix){
        $t = new Traverser($prefix . "\\", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isValidEscape($t), FALSE));
        self::assertTrue(match($t->eatAll(), "\\"));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_TRUE_if_backslash_is_not_followed_by_newline_or_EOF(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getNewlinesSet());
        return cartesianProduct(
            ANY_STRING(),
            getCodePointsFromRanges($codePoints),
            ANY_STRING()
        );
    }

    /** @dataProvider data_returns_TRUE_if_backslash_is_not_followed_by_newline_or_EOF */
    function test_returns_TRUE_if_backslash_is_not_followed_by_newline_or_EOF(String $prefix, String $notNewlineCodePoint, String $rest){
        $t = new Traverser($prefix . "\\" . $notNewlineCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isValidEscape($t), TRUE));
        self::assertTrue(match($t->eatAll(), "\\" . $notNewlineCodePoint . $rest));
    }
}
