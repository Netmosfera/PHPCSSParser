<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names\URLs;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\TokensChecked\sampleURLIdentifiers;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeURLPieceAfterPieceFunction;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test identifier's intended value does not match "url"
 * #3 | test contiguous bits are disallowed
 */
class URLTokenTest extends TestCase
{
    public function data1(){
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
        $wsBefore1 = new CheckedWhitespaceToken(" \r\n\t\f");
        $wsBefore2 = new CheckedWhitespaceToken(" \r\n\t\f");
        $wsAfter1 = new CheckedWhitespaceToken("\t\f \r\n");
        $wsAfter2 = new CheckedWhitespaceToken("\t\f \r\n");
        $URL1 = new CheckedURLToken($identifier1, $wsBefore1, $pieces1, $wsAfter1, FALSE);
        $URL2 = new CheckedURLToken($identifier2, $wsBefore2, $pieces2, $wsAfter2, FALSE);
        assertMatch($URL1, $URL2);
        assertMatch((String)$URL1, $identifier1 . "(" . $wsBefore2 . implode("", $pieces1) . $wsAfter2 . ")");
        assertMatch($URL1->EOFTerminated(), FALSE);
        assertMatch($URL1->identifier(), $identifier2);
        assertMatch($URL1->pieces(), $pieces2);
        assertMatch($URL1->whitespaceBefore(), $wsBefore2);
        assertMatch($URL1->whitespaceAfter(), $wsAfter2);
    }

    public function test2(){
        $nameBit = new CheckedNameBitToken("nope");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        assertThrowsType(InvalidToken::CLASS, function() use($identifier){
            new CheckedURLToken($identifier, NULL, [], NULL, FALSE);
        });
    }

    public function test3(){
        $nameBit = new CheckedNameBitToken("url");
        $name = new CheckedNameToken([$nameBit]);
        $identifier = new CheckedIdentifierToken($name);
        $pieces[] = new CheckedURLBitToken("foo");
        $pieces[] = new CheckedURLBitToken("bar");
        assertThrowsType(InvalidToken::CLASS, function() use($identifier, $pieces){
            new CheckedURLToken($identifier, NULL, $pieces, NULL, FALSE);
        });
    }
}
