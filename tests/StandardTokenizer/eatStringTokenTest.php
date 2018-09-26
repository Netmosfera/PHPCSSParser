<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatStringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
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
        $eatEscape = function(Traverser $t): ?EscapeToken{ self::fail(); };
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
        $pieces = $string === "" ? [] : [new StringBitToken($string)];
        $expected = new StringToken($delimiter, $pieces, TRUE);
        $eatEscape = function(Traverser $t): ?EscapeToken{ self::fail(); };
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
        $pieces = $string === "" ? [] : [new StringBitToken($string)];
        $expected = new BadStringToken($delimiter, $pieces);
        $eatEscape = function(Traverser $t): ?EscapeToken{ self::fail(); };
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
        $expected = new StringToken($delimiter, $pieces, FALSE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return $traverser->eatStr("\\@") === NULL ? NULL : new EncodedCodePointEscapeToken("@");
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function getPieces($afterPiece){
        if(!$afterPiece instanceof StringBitToken){
            $data[] = new StringBitToken("s");
            $data[] = new StringBitToken("st");
            $data[] = new StringBitToken("str");
        }
        $data[] = new EncodedCodePointEscapeToken("@");
        return $data;
    }
}
