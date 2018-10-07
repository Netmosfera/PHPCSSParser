<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeIdentifierPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid identifier start
 */
class IdentifierTokenTest extends TestCase
{
    function data1(){
        $pieces1 = makePiecesSample(makeIdentifierPieceAfterPieceFunction(), FALSE);
        $pieces2 = makePiecesSample(makeIdentifierPieceAfterPieceFunction(), FALSE);
        $groupedPieces = groupByOffset($pieces1, $pieces2);
        return cartesianProduct($groupedPieces);
    }

    /** @dataProvider data1 */
    public function test1(array $groupedPieces){
        [$pieces1, $pieces2] = $groupedPieces;
        $name1 = new CheckedNameToken($pieces1);
        $name2 = new CheckedNameToken($pieces2);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        assertMatch($identifier1, $identifier2);
        assertMatch((String)$identifier1, implode("", $pieces1));
        assertMatch($identifier1->name(), $name2);
    }

    public function data2(){
        $pieces = [];
        $pieces[] = new CheckedNameBitToken("3");
        yield [$pieces];

        $pieces = [];
        $pieces[] = new CheckedNameBitToken("3foo");
        yield [$pieces];

        $pieces = [];
        $pieces[] = new CheckedNameBitToken("-3");
        yield [$pieces];

        $pieces = [];
        $pieces[] = new CheckedNameBitToken("-3foo");
        yield [$pieces];

        $pieces = [];
        $pieces[] = new CheckedNameBitToken("-");
        yield [$pieces];
    }

    /** @dataProvider data2 */
    public function test2(array $pieces){
        $name = new CheckedNameToken($pieces);
        assertThrowsType(InvalidToken::CLASS, function() use($name){
            new CheckedIdentifierToken($name);
        });
    }
}
