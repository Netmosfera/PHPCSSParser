<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Tokenizer\eatBadURLRemnantsToken;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Tokenizer\Fakes\eatEscapeTokenFunction;

/**
 * Tests in this file:
 *
 * #1  | test terminated
 * #2  | test unterminated (EOF)
  */
class eatBadURLRemnantsTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(FALSE), FALSE),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, array $pieces, String $rest){
        $badURLRemnants = new BadURLRemnantsToken($pieces, FALSE);

        $traverser = getTraverser($prefix, $badURLRemnants . $rest);
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(makeBadURLRemnantsPieceAfterPieceFunction(TRUE), FALSE)
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, array $pieces){
        $EOFTerminated = end($pieces) instanceof EOFEscapeToken;
        $badURLRemnants = new BadURLRemnantsToken($pieces, $EOFTerminated);

        $traverser = getTraverser($prefix, $badURLRemnants . "");
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), "");
    }
}
