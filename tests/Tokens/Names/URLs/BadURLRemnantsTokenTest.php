<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names\URLs;

use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeBadURLRemnantsPieceAfterPieceFunction;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test getters EOF terminated
 */
class BadURLRemnantsTokenTest extends TestCase
{
    function data1(){
        $pieces1 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(FALSE), FALSE);
        $pieces2 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(FALSE), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $name1 = new BadURLRemnantsToken($pieces1, FALSE);
        $name2 = new BadURLRemnantsToken($pieces2, FALSE);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1) . ")");
        assertMatch($name1->EOFTerminated(), FALSE);
        assertMatch($name1->pieces(), $pieces2);
    }

    function data2(){
        $pieces1 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(TRUE), FALSE);
        $pieces2 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(TRUE), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data2 */
    public function test2(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $name1 = new BadURLRemnantsToken($pieces1, TRUE);
        $name2 = new BadURLRemnantsToken($pieces2, TRUE);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1));
        assertMatch($name1->EOFTerminated(), TRUE);
        assertMatch($name1->pieces(), $pieces2);
    }
}
