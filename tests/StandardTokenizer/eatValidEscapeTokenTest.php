<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatValidEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #0 | NULL if not starting with backslash
 * #1 | NULL if EOF
 * #2 | Token if hex digits
 * #3 | Token if code point
 * #4 | NULL if continuation
 */
class eatValidEscapeTokenTest extends TestCase
{
    function data0(){
        return cartesianProduct(ANY_UTF8(), ["not escape", ""]);
    }

    /** @dataProvider data0 */
    function test0(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $actual = eatValidEscapeToken($traverser, "D", "\t", "\f");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data1(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data1 */
    function test1(String $prefix){
        $traverser = getTraverser($prefix, "\\");
        $expected = NULL;
        $actual = eatValidEscapeToken($traverser, "D", "\t", "\f");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\\");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["D", "DD", "DDD", "DDDD", "DDDDD", "DDDDDD"],
            ["", "\t"],
            ["", "sample \u{2764} string"]
        );
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $hexDigits, String $whitespace, String $rest){
        $traverser = getTraverser($prefix, "\\" . $hexDigits . $whitespace . $rest);
        $expected = new CheckedCodePointEscapeToken($hexDigits, $whitespace === "" ? NULL : new CheckedWhitespaceToken($whitespace));
        $actual = eatValidEscapeToken($traverser, "D", "\t", "\n");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getHexDigitsSet());
        $set->removeAll(getNewlinesSet());
        $sequences = getCodePointsFromRanges($set);
        return cartesianProduct(
            ANY_UTF8(),
            $sequences,
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $codePoint, String $rest){
        $traverser = getTraverser($prefix, "\\" . $codePoint . $rest);
        $expected = new CheckedEncodedCodePointEscapeToken($codePoint);
        $actual = eatValidEscapeToken($traverser, "D", "\t", "\n");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    function test4(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "\\" . "\n" . $rest);
        $expected = NULL;
        $actual = eatValidEscapeToken($traverser, "D", "\t", "\n");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\\" . "\n" . $rest);
    }
}
