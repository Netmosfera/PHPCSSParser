<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Strings;

use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
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
    public function test1(String $delimiter, array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $badString1 = new BadStringToken($delimiter, $pieces1);
        $badString2 = new BadStringToken($delimiter, $pieces2);
        assertMatch($badString1, $badString2);
        assertMatch((String)$badString1, $delimiter . implode("", $pieces1));
        assertMatch($badString1->delimiter(), $delimiter);
        assertMatch($badString1->intendedValue(), $intendedValue);
        assertMatch($badString1->pieces(), $pieces2);
    }
}
