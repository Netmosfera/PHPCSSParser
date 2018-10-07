<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names\URLs;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeURLPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\TokensChecked\sampleURLIdentifiers;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test identifier's intended value does not match "url"
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
    public function test1(Array $identifiers, Array $groupedPieces){
        [$identifier1, $identifier2] = $identifiers;
        [$pieces1, $pieces2] = $groupedPieces;
        $wsBefore1 = new CheckedWhitespaceToken(" \r\n\t\f");
        $wsBefore2 = new CheckedWhitespaceToken(" \r\n\t\f");
        $remnants1 = new CheckedBadURLRemnantsToken([new CheckedBadURLRemnantsBitToken("( fail")], FALSE);
        $remnants2 = new CheckedBadURLRemnantsToken([new CheckedBadURLRemnantsBitToken("( fail")], FALSE);
        $URL1 = new CheckedBadURLToken($identifier1, $wsBefore1, $pieces1, $remnants1);
        $URL2 = new CheckedBadURLToken($identifier2, $wsBefore2, $pieces2, $remnants2);
        assertMatch($URL1, $URL2);
        assertMatch((String)$URL1, $identifier2 . "(" . $wsBefore2 . implode("", $pieces1) . $remnants1);
        assertMatch($URL1->identifier(), $identifier2);
        assertMatch($URL1->whitespaceBefore(), $wsBefore2);
        assertMatch($URL1->pieces(), $pieces2);
        assertMatch($URL1->remnants(), $remnants2);
    }

    public function test2(){
        $nameBit = new CheckedNameBitToken("nope");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $remnants = new CheckedBadURLRemnantsToken([new CheckedBadURLRemnantsBitToken("(")], FALSE);
        assertThrowsType(InvalidToken::CLASS, function() use($identifier, $remnants){
            new CheckedBadURLToken($identifier, NULL, [], $remnants);
        });
    }

    public function test3(){
        $nameBit = new CheckedNameBitToken("url");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $pieces[] = new CheckedURLBitToken("foo");
        $pieces[] = new CheckedURLBitToken("bar");
        $remnantsBit = new CheckedBadURLRemnantsBitToken("(");
        $remnants = new CheckedBadURLRemnantsToken([$remnantsBit], FALSE);
        assertThrowsType(InvalidToken::CLASS, function() use($identifier, $pieces, $remnants){
            new CheckedBadURLToken($identifier, NULL, $pieces, $remnants);
        });
    }
}
