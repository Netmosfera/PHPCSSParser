<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatStringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\Escape;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | NULL if no string delimiter
 * #2 | StringToken if string is terminated with EOF
 * #3 | Returns BadStringToken if string is interrupted by a newline
 * #4 | test loop @TODO repeat the test with EOF and bad string token
 */
class eatStringTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            ["", "not a \u{2764} CSS string"]
        );
    }

    /** @dataProvider data1 */
    function test1($prefix, $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $t): ?Escape{ self::fail(); };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            ["", "skip \u{2764} me"]
        );
    }

    /** @dataProvider data2 */
    function test2($prefix, $delimiter, $string){
        $traverser = getTraverser($prefix, $delimiter . $string);
        $expected = new StringToken($delimiter, $string === "" ? [] : [$string], TRUE);
        $eatEscape = function(Traverser $t): ?Escape{ self::fail(); };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            ["", "skip \u{2764} me"],
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    function test3($prefix, $delimiter, $string, $rest){
        $traverser = getTraverser($prefix, $delimiter . $string . "\f" . $rest);
        $expected = new BadStringToken($delimiter, $string === "" ? [] : [$string]);
        $eatEscape = function(Traverser $t): ?Escape{ self::fail(); };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ANY_UTF8()
        );
    }

    /** @dataProvider data4 */
    function test4(String $prefix, String $delimiter, Array $pieces, String $rest){
        $traverser = getTraverser($prefix, $delimiter . implode("", $pieces) . $delimiter . $rest);
        $expected = new StringToken($delimiter, $pieces);
        $eatEscape = function(Traverser $traverser): ?Escape{
            return $traverser->eatStr("\\@") === NULL ? NULL : new CodePointEscape("@");
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function getPieces($afterPiece){
        if(!is_string($afterPiece)){
            $data[] = "s";
            $data[] = "st";
            $data[] = "str";
        }
        $data[] = new CodePointEscape("@");
        return $data;
    }
}
