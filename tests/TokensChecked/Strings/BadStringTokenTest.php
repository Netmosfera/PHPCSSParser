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
 * #2 | test contiguous bits
 */
class BadStringTokenTest extends TestCase
{
    function data1(){
        $pieces1 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $pieces2 = makePiecesSample(makeStringPieceAfterPieceFunction(FALSE));
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(Array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $badString1 = new CheckedBadStringToken("'", $pieces1);
        $badString2 = new CheckedBadStringToken("'", $pieces2);
        assertMatch($badString1, $badString2);
        assertMatch((String)$badString1, "'" . implode("", $pieces1));
        assertMatch($badString1->delimiter(), "'");
        assertMatch($badString1->intendedValue(), $intendedValue);
        assertMatch($badString1->pieces(), $pieces2);
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
