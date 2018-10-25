<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Strings;

use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\TokensChecked\piecesIntendedValue;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeStringPieceAfterPieceFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test eof-terminated getters
 * #3 | test contiguous bits
 * #4 | test eofescape not last
 * #5 | test last eofescape enforces terminated with eof flag
  */
class StringTokenTest extends TestCase
{
    public function data1(){
        $pieces1 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $pieces2 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct(["\"", "'"], $groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(String $delimiter, array $groupedPieces){
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

    public function data2(){
        $pieces1 = makePiecesSample(makeStringPieceAfterPieceFunction(TRUE));
        $pieces2 = makePiecesSample(makeStringPieceAfterPieceFunction(TRUE));
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct(["\"", "'"], $groupedPieces);
    }

    /** @dataProvider data2 */
    public function test2(String $delimiter, array $groupedPieces){
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

    public function test3(){
        $pieces[] = new CheckedStringBitToken("foo");
        $pieces[] = new CheckedStringBitToken("bar");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedStringToken("'", $pieces, FALSE);
        });
    }

    public function test4(){
        $pieces[] = new CheckedStringBitToken("foo");
        $pieces[] = new EOFEscapeToken();
        $pieces[] = new CheckedStringBitToken("bar");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedStringToken("'", $pieces, FALSE);
        });
    }

    public function test5(){
        $pieces[] = new CheckedStringBitToken("foo");
        $pieces[] = new EOFEscapeToken();
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedStringToken("'", $pieces, FALSE);
        });
    }
}
