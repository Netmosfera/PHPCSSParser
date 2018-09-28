<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatNumericToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | not numeric
 * #2 | percentage
 * #3 | dimension
 * #4 | number
 */
class eatNumericTokenTest extends TestCase
{
    function data1(){
        $rest[] = "";
        $rest[] = "sample \u{2764} string";
        return cartesianProduct(ANY_UTF8(), $rest);
    }

    /** @dataProvider data1 */
    function test1(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatNumberToken = function(Traverser $traverser){ return NULL; };
        $eatIdentifierToken = function(Traverser $traverser){ self::fail(); };
        $actual = eatNumericToken($traverser, $eatNumberToken, $eatIdentifierToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data2 */
    function test2(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "abc%" . $rest);
        $number = new CheckedNumberToken("+", "2398", "42", "", "", "");
        $expected = new CheckedPercentageToken($number);
        $eatNumberToken = function(Traverser $traverser) use($number){
            assertNotMatch($traverser->eatStr("abc"), NULL);
            return $number;
        };
        $eatIdentifierToken = function(Traverser $traverser){ self::fail(); };
        $actual = eatNumericToken($traverser, $eatNumberToken, $eatIdentifierToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "abcdef" . $rest);
        $number = new CheckedNumberToken("+", "2398", "42", "", "", "");
        $unit = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("attoparsec")]));
        $expected = new CheckedDimensionToken($number, $unit);
        $eatNumberToken = function(Traverser $traverser) use($number){
            assertNotMatch($traverser->eatStr("abc"), NULL);
            return $number;
        };
        $eatIdentifierToken = function(Traverser $traverser) use($unit){
            assertNotMatch($traverser->eatStr("def"), NULL);
            return $unit;
        };
        $actual = eatNumericToken($traverser, $eatNumberToken, $eatIdentifierToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    function test4(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "abc" . $rest);
        $number = new CheckedNumberToken("+", "42", "24", "", "", "");
        $expected = $number;
        $eatNumberToken = function(Traverser $traverser) use($number){
            assertNotMatch($traverser->eatStr("abc"), NULL);
            return $number;
        };
        $eatIdentifierToken = function(Traverser $traverser){ return NULL; };
        $actual = eatNumericToken($traverser, $eatNumberToken, $eatIdentifierToken);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
