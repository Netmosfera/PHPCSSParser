<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeIdentifierPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\piecesIntendedValue;

/**
 * Tests in this file:
 *
 * #1 | test getters
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
    public function test1(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $intendedValue = piecesIntendedValue($pieces1);
        $name1 = new NameToken($pieces1);
        $name2 = new NameToken($pieces2);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1));
        assertMatch($name1->intendedValue(), $intendedValue);
        assertMatch($name1->pieces(), $pieces2);
    }
}
