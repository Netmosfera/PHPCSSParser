<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names\URLs;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeBadURLRemnantsPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test getters EOF terminated
 * #3 | test empty pieces
 * #4 | test contiguous bits
 * #5 | test not starting with invalid bit
 * #6 | test not starting with invalid escape
 * #7 | test eofescape not last
 * #8 | test last eofescape enforces terminated with eof flag
 */
class BadURLRemnantsTokenTest extends TestCase
{
    public function data1(){
        $pieces1 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(FALSE), FALSE);
        $pieces2 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(FALSE), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $name1 = new CheckedBadURLRemnantsToken($pieces1, FALSE);
        $name2 = new CheckedBadURLRemnantsToken($pieces2, FALSE);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1) . ")");
        assertMatch($name1->EOFTerminated(), FALSE);
        assertMatch($name1->pieces(), $pieces2);
    }

    public function data2(){
        $pieces1 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(TRUE), FALSE);
        $pieces2 = makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(TRUE), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data2 */
    public function test2(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $name1 = new CheckedBadURLRemnantsToken($pieces1, TRUE);
        $name2 = new CheckedBadURLRemnantsToken($pieces2, TRUE);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1));
        assertMatch($name1->EOFTerminated(), TRUE);
        assertMatch($name1->pieces(), $pieces2);
    }

    public function test3(){
        assertThrowsType(InvalidToken::CLASS, function(){
            new CheckedBadURLRemnantsToken([], FALSE);
        });
    }

    public function test4(){
        $pieces[] = new CheckedBadURLRemnantsBitToken("(foo");
        $pieces[] = new CheckedBadURLRemnantsBitToken("bar");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadURLRemnantsToken($pieces, FALSE);
        });
    }

    public function test5(){
        $pieces[] = new CheckedBadURLRemnantsBitToken("foo");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadURLRemnantsToken($pieces, FALSE);
        });
    }

    public function test6(){
        $pieces[] = new CheckedEncodedCodePointEscapeToken("x");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadURLRemnantsToken($pieces, FALSE);
        });
    }

    public function test7(){
        $pieces[] = new CheckedBadURLRemnantsBitToken("(foo");
        $pieces[] = new EOFEscapeToken();
        $pieces[] = new CheckedBadURLRemnantsBitToken("bar");
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadURLRemnantsToken($pieces, FALSE);
        });
    }

    public function test8(){
        $pieces[] = new CheckedBadURLRemnantsBitToken("(foo");
        $pieces[] = new CheckedContinuationEscapeToken("\n");
        $pieces[] = new EOFEscapeToken();
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadURLRemnantsToken($pieces, FALSE);
        });
    }
}
