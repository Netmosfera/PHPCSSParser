<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Tokenizer\eatIdentifierToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatValidEscapeTokenFunction;

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
    public function test1(String $prefix, array $pieces, String $rest){
        $name = new NameToken($pieces);
        $identifier = new IdentifierToken($name);

        $traverser = getTraverser($prefix, $identifier . $rest);
        $eatEscape = eatValidEscapeTokenFunction($pieces);
        $actualIdentifier = eatIdentifierToken($traverser, "S", "N", $eatEscape);

        assertMatch($actualIdentifier, $identifier);
        assertMatch($traverser->eatAll(), $rest);
    }

    // @TODO test 2 and tests that return NULL
}
