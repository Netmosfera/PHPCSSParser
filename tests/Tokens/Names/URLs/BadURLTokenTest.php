<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names\URLs;

use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeURLPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\Tokenizer\sampleURLIdentifiers;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class BadURLTokenTest extends TestCase
{
    function data1(){
        $identifiers = groupByOffset(sampleURLIdentifiers(), sampleURLIdentifiers());
        $pieces1 = makePiecesSample(makeURLPieceAfterPieceFunction());
        $pieces2 = makePiecesSample(makeURLPieceAfterPieceFunction());
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($identifiers, $groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(array $identifiers, array $groupedPieces){
        [$identifier1, $identifier2] = $identifiers;
        [$pieces1, $pieces2] = $groupedPieces;
        $wsBefore1 = new WhitespaceToken(" \r\n\t\f");
        $wsBefore2 = new WhitespaceToken(" \r\n\t\f");
        $remnants1 = new BadURLRemnantsToken([new BadURLRemnantsBitToken("( fail")], FALSE);
        $remnants2 = new BadURLRemnantsToken([new BadURLRemnantsBitToken("( fail")], FALSE);
        $URL1 = new BadURLToken($identifier1, $wsBefore1, $pieces1, $remnants1);
        $URL2 = new BadURLToken($identifier2, $wsBefore2, $pieces2, $remnants2);
        assertMatch($URL1, $URL2);
        assertMatch((String)$URL1, $identifier2 . "(" . $wsBefore2 . implode("", $pieces1) . $remnants1);
        assertMatch($URL1->identifier(), $identifier2);
        assertMatch($URL1->whitespaceBefore(), $wsBefore2);
        assertMatch($URL1->pieces(), $pieces2);
        assertMatch($URL1->remnants(), $remnants2);
    }
}
