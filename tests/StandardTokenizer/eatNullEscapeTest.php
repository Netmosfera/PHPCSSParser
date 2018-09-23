<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscape;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatNullEscape;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #0 | not starting with \
 * #1 | EOF
 * #2 | newline (continuation)
 * #3 | valid escape
 */
class eatNullEscapeTest extends TestCase
{
    function data0(){
        return cartesianProduct(ANY_UTF8(), ["not escape", ""]);
    }

    /** @dataProvider data0 */
    function test0(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $actual = eatNullEscape($traverser, "\n");
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
        $expected = new EOFEscape();
        $actual = eatNullEscape($traverser, "irrelevant");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "\\\f" . $rest);
        $expected = new ContinuationEscape("\f");
        $actual = eatNullEscape($traverser, "\f");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), ["f", "FFAACC", "x", "a", "z", "@"], ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $validEscape, String $rest){
        $traverser = getTraverser($prefix, "\\" . $validEscape . $rest);
        $expected = NULL;
        $actual = eatNullEscape($traverser, "\x{C}");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\\" . $validEscape . $rest);
    }
}
