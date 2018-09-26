<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\HexEscape;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatURLToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * @TODO improve these
 *
 * #0  |                NULL if starts with whitespace* followed by string delimiter
 *
 * #1  |                EOF
 * #2  |                ) + rest
 * #3  |                invalid escape -> remnants
 * #4  |                valid escape
 * #5  |                blacklisted code point -> remnants
 * #6  |                sequence
 *
 * #7  |     sequence + EOF
 * #8  |     sequence + ) + rest
 * #9  |     sequence + invalid escape -> remnants
 * #10 |     sequence + valid escape
 * #11 |     sequence + blacklisted code point -> remnants
 * #12 |     sequence + ws + not ) or EOF
 */
class eatURLTokenTest extends TestCase
{
    function data0(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""], ["\"", "'"], ANY_UTF8());
    }

    /** @dataProvider data0 */
    function test0(String $prefix, String $startWS, String $stringDelimiter, String $rest){
        $traverser = getTraverser($prefix, $startWS . $stringDelimiter . $rest);
        $expected = NULL;
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $startWS . $stringDelimiter . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data1(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""]);
    }

    /** @dataProvider data1 */
    function test1($prefix, $startWS){
        $traverser = getTraverser($prefix, $startWS);
        $expected = new URLToken($startWS, [], "", TRUE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""], ANY_UTF8());
    }

    /** @dataProvider data2 */
    function test2($prefix, $startWS, $rest){
        $traverser = getTraverser($prefix, $startWS . ")" . $rest);
        $expected = new URLToken($startWS, [], "", FALSE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""], ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3($prefix, $startWS, $rest){
        $traverser = getTraverser($prefix, $startWS . "\\\n" . $rest);
        $remnants = new BadURLRemnantsToken(["irrelevant"]);
        $expected = new BadURLToken($startWS, [], $remnants);
        $eatEscape = function(Traverser $traverser){
            assertNotMatch($traverser->createBranch()->eatStr("\\\n"), NULL);
            return NULL;
        };
        $eatRemnants = function(Traverser $traverser) use($remnants){
            assertNotMatch($traverser->eatStr("\\\n"), NULL);
            return $remnants;
        };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data4 */
    function test4($prefix, $startWS, $rest){
        $ve = "\\66ff";
        $traverser = getTraverser($prefix, $startWS . $ve . ")" . $rest);
        $escape = new HexEscape($ve, NULL);
        $expected = new URLToken($startWS, [$escape], "", FALSE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser) use($escape, $ve){
            assertNotMatch($traverser->eatStr($ve), NULL);
            return $escape;
        };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data5(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data5 */
    function test5($prefix, $startWS, $rest){
        $ic = "6 invalid code point";
        $traverser = getTraverser($prefix, $startWS . $ic . $rest);
        $remnants = new BadURLRemnantsToken(["irrelevant"]);
        $expected = new BadURLToken($startWS, [], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ic){
            assertNotMatch($traverser->eatStr($ic), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data6(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data6 */
    function test6($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . ")" . $rest);
        $expected = new URLToken($startWS, [$vs], "", FALSE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data7(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""]);
    }

    /** @dataProvider data7 */
    function test7($prefix, $startWS, $endWS){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . $endWS);
        $expected = new URLToken($startWS, [$vs], $endWS, TRUE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data8(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data8 */
    function test8($prefix, $startWS, $endWS, $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . $endWS . ")" . $rest);
        $expected = new URLToken($startWS, [$vs], $endWS, FALSE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data9(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data9 */
    function test9($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ie = "\\\n invalid escape";
        $traverser = getTraverser($prefix, $startWS . $vs . $ie . $rest);
        $remnants = new BadURLRemnantsToken(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ie){
            assertNotMatch($traverser->eatStr($ie), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser) use($ie){
            assertNotMatch($traverser->createBranch()->eatStr($ie), NULL);
            return NULL;
        };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data10(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data10 */
    function test10($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ve = "\\66ff";
        $traverser = getTraverser($prefix, $startWS . $vs . $ve . ")" . $rest);
        $escape = new HexEscape("FFaaCC", NULL);
        $expected = new URLToken($startWS, [$vs, $escape], "", FALSE);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = function(Traverser $traverser) use($escape, $ve){
            assertNotMatch($traverser->eatStr($ve), NULL);
            return $escape;
        };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data11(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data11 */
    function test11($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $ic = "6 invalid code point";
        $traverser = getTraverser($prefix, $startWS . $vs . $ic . $rest);
        $remnants = new BadURLRemnantsToken(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ic){
            assertNotMatch($traverser->eatStr($ic), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data12(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data12 */
    function test12($prefix, $startWS, $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . "\f remnants" . $rest);
        $remnants = new BadURLRemnantsToken(["irrelevant"]);
        $expected = new BadURLToken($startWS, [$vs], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants){
            assertNotMatch($traverser->eatStr("\f remnants"), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){ self::fail(); };
        $actual = eatURLToken($traverser, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data24(){
        return cartesianProduct(
            ANY_UTF8(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data24 */
    function test24(String $prefix, String $rest){
        $startWS = "\f";
        $endWS = "\f";
        $pieces[] = "string1";
        $pieces[] = $escapes[] = new CodePointEscape("x");
        $pieces[] = $escapes[] = new CodePointEscape("y");
        $pieces[] = $escapes[] = new CodePointEscape("z");
        $pieces[] = "string2";
        $pieces[] = $escapes[] = new CodePointEscape("0");
        $pieces[] = $escapes[] = new CodePointEscape("1");
        $pieces[] = "string3";
        $pieces[] = $escapes[] = new CodePointEscape("@");
        $pieces[] = "string4";
        $pieces[] = $escapes[] = new CodePointEscape("#");
        $pieces[] = "string5";
        $URL = implode("", $pieces);
        $traverser = getTraverser($prefix, $startWS . $URL . $endWS . ")" . $rest);
        $eatRemnants = function(Traverser $traverser){ self::fail(); };
        $eatEscape = makeEatEscapeFunctionFromEscapeList($escapes);
        $expected = eatURLToken($traverser, "\f", "@", $eatEscape, $eatRemnants);
        $actual = new URLToken($startWS, $pieces, $endWS, FALSE);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
