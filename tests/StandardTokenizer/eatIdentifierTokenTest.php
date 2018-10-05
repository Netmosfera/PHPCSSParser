<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatValidEscapeTokenFunction;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeIdentifierPieceAfterPieceFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 covers all working cases except #2
 * #2 covers "-" followed by valid escape
 */
class eatIdentifierTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(makeIdentifierPieceAfterPieceFunction(), FALSE),
            ANY_UTF8("not starting with N (a name code point)")
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, Array $pieces, String $rest){
        $escape1 = new CheckedCodePointEscapeToken("2764", NULL);
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $name = new CheckedNameToken($pieces);
        $identifier = new CheckedIdentifierToken($name);

        $traverser = getTraverser($prefix, $identifier . $rest);
        $eatEscape = eatValidEscapeTokenFunction([$escape1, $escape2]);
        $actualIdentifier = eatIdentifierToken($traverser, "S", "N", $eatEscape);

        assertMatch($actualIdentifier, $identifier);
        assertMatch($traverser->eatAll(), $rest);
    }

    // @TODO test 2 and tests that return NULL
}
