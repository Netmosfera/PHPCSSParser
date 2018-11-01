<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\groupByOffset;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\makeIdentifierPieceAfterPieceFunction;

/**
 * Tests in this file:
 *
 * #1 | test getters
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
        $name1 = new NameToken($pieces1);
        $name2 = new NameToken($pieces2);
        $identifier1 = new IdentifierToken($name1);
        $identifier2 = new IdentifierToken($name2);
        assertMatch($identifier1, $identifier2);
        assertMatch((String)$identifier1, implode("", $pieces1));
        assertMatch($identifier1->name(), $name2);
    }
}
