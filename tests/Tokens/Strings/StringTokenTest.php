<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Strings;

use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeStringPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\piecesIntendedValue;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test eof-terminated getters
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
    public function test1(String $delimiter, array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $string1 = new StringToken($delimiter, $pieces1, FALSE);
        $string2 = new StringToken($delimiter, $pieces2, FALSE);
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
    public function test2(String $delimiter, array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $string1 = new StringToken($delimiter, $pieces1, TRUE);
        $string2 = new StringToken($delimiter, $pieces2, TRUE);
        assertMatch($string1, $string2);
        assertMatch((String)$string1, $delimiter . implode("", $pieces1));
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->EOFTerminated(), TRUE);
        assertMatch($string1->pieces(), $pieces2);
    }
}
