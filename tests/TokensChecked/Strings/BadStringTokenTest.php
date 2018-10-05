<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Strings;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\TokensChecked\piecesIntendedValue;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeStringPieceAfterPieceFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class BadStringTokenTest extends TestCase
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
        $string1 = new CheckedBadStringToken($delimiter, $pieces1);
        $string2 = new CheckedBadStringToken($delimiter, $pieces2);
        assertMatch($string1, $string2);
        assertMatch((String)$string1, $delimiter . implode("", $pieces1));
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->pieces(), $pieces2);
    }

    public function data2(){
        yield [[
            new CheckedEncodedCodePointEscapeToken("@"),
            new StringBitToken("abc"),
            new StringBitToken("def"),
            new CheckedEncodedCodePointEscapeToken("@"),
        ]];
    }

    /** @dataProvider data2 */
    public function test2(Array $pieces){
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadStringToken("'", $pieces);
        });
    }
}
