<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\TokensChecked\piecesIntendedValue;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeIdentifierPieceAfterPieceFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class NameTokenTest extends TestCase
{
    function data1(){
        $pieces1 = makePiecesSample(makeIdentifierPieceAfterPieceFunction(), FALSE);  // @TODO make namepiece
        $pieces2 = makePiecesSample(makeIdentifierPieceAfterPieceFunction(), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(Array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $name1 = new CheckedNameToken($pieces1);
        $name2 = new CheckedNameToken($pieces2);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1));
        assertMatch($name1->intendedValue(), $intendedValue);
        assertMatch($name1->pieces(), $pieces2);
    }

    // @TODO test invalid cases
}
