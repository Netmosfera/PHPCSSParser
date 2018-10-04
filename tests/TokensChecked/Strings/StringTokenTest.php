<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Strings;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\TokensChecked\piecesIntendedValue;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeStringPieceAfterPieceFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;
use TypeError;
use stdClass;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test terminated with eof getters
 * #3 | test invalid
  */
class StringTokenTest extends TestCase
{
    function data1(){
        $pieces1 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $pieces2 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct(["\"", "'"], $groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(String $delimiter, Array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $string1 = new CheckedStringToken($delimiter, $pieces1, FALSE);
        $string2 = new CheckedStringToken($delimiter, $pieces2, FALSE);
        assertMatch($string1, $string2);
        assertMatch((String)$string1, $delimiter . implode("", $pieces1) . $delimiter);
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->EOFTerminated(), FALSE);
        assertMatch($string1->pieces(), $pieces2);
    }

    function data2(){
        $pieces1 = makePiecesSample(makeStringPieceAfterPieceFunction(TRUE));
        $pieces2 = makePiecesSample(makeStringPieceAfterPieceFunction(TRUE));
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct(["\"", "'"], $groupedPieces);
    }

    /** @dataProvider data2 */
    public function test2(String $delimiter, Array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $string1 = new CheckedStringToken($delimiter, $pieces1, TRUE);
        $string2 = new CheckedStringToken($delimiter, $pieces2, TRUE);
        assertMatch($string1, $string2);
        assertMatch((String)$string1, $delimiter . implode("", $pieces1));
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->EOFTerminated(), TRUE);
        assertMatch($string1->pieces(), $pieces2);
    }

    public function data3(){
        yield [[
            0 => new CheckedEncodedCodePointEscapeToken("@"),
            1 => new CheckedEncodedCodePointEscapeToken("@"),
            3 => new CheckedEncodedCodePointEscapeToken("@"),
            2 => new CheckedEncodedCodePointEscapeToken("@"),
        ], FALSE];
        yield [[
            new stdClass()
        ], FALSE];
    }

    /** @dataProvider data3 */
    public function test3(Array $pieces, Bool $EOFTerminated){
        assertThrowsType(TypeError::CLASS, function() use($pieces, $EOFTerminated){
            new CheckedStringToken("'", $pieces, $EOFTerminated);
        });
    }

    public function data4(){
        yield [[
            new CheckedEncodedCodePointEscapeToken("@"),
            new StringBitToken("abc"),
            new StringBitToken("def"),
            new CheckedEncodedCodePointEscapeToken("@"),
        ], FALSE];

        yield [[
            new StringBitToken("abc"),
            new EOFEscapeToken(),
        ], FALSE];
    }

    /** @dataProvider data4 */
    public function test4(Array $pieces, Bool $EOFTerminated){
        assertThrowsType(InvalidToken::CLASS, function() use($pieces, $EOFTerminated){
            new CheckedStringToken("'", $pieces, $EOFTerminated);
        });
    }
}
