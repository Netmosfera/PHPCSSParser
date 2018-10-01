<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use PHPUnit\Framework\TestCase;
use Closure;

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
    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            ["", "not a \u{2764} CSS string"]
        );
    }

    /** @dataProvider data1 */
    public function test1($prefix, $rest){
        $traverser = getTraverser($prefix, $rest);
        $expected = NULL;
        $eatEscape = function(Traverser $t): ?EscapeToken{
            self::fail();
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            ["", "skip \u{2764} me"]
        );
    }

    /** @dataProvider data2 */
    public function test2($prefix, $delimiter, $string){
        $traverser = getTraverser($prefix, $delimiter . $string);
        $pieces = $string === "" ? [] : [new CheckedStringBitToken($string)];
        $expected = new CheckedStringToken($delimiter, $pieces, TRUE);
        $eatEscape = function(Traverser $t): ?EscapeToken{
            self::fail();
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            ["", "skip \u{2764} me"],
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3($prefix, $delimiter, $string, $rest){
        $traverser = getTraverser($prefix, $delimiter . $string . "\f" . $rest);
        $pieces = $string === "" ? [] : [new CheckedStringBitToken($string)];
        $expected = new CheckedBadStringToken($delimiter, $pieces);
        $eatEscape = function(Traverser $t): ?EscapeToken{
            self::fail();
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(Closure::fromCallable([$this, "getPieces"])),
            ANY_UTF8()
        );
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $delimiter, Array $pieces, String $rest){
        $string = implode("", $pieces);
        $traverser = getTraverser($prefix, $delimiter . $string . $delimiter . $rest);
        $expected = new CheckedStringToken($delimiter, $pieces, FALSE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            if($traverser->eatStr("\\@") === NULL){ return NULL; }
            return new CheckedEncodedCodePointEscapeToken("@");
        };
        $actual = eatStringToken($traverser, "\f", $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function getPieces($afterPiece){
        if(!$afterPiece instanceof StringBitToken){
            $data[] = new CheckedStringBitToken("s");
            $data[] = new CheckedStringBitToken("st");
            $data[] = new CheckedStringBitToken("str");
        }
        $data[] = new CheckedEncodedCodePointEscapeToken("@");
        return $data;
    }
}
