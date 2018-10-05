<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use function Netmosfera\PHPCSSASTTests\TokensChecked\makeBadURLRemnantsPieceAfterPieceFunction;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatEscapeTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatBadURLRemnantsToken;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

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
    public function test1(String $prefix, Array $pieces, String $rest){
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, FALSE);

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
    public function test2(String $prefix, Array $pieces){
        $EOFTerminated = end($pieces) instanceof EOFEscapeToken;
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, $EOFTerminated);

        $traverser = getTraverser($prefix, $badURLRemnants . "");
        $eatEscape = eatEscapeTokenFunction($pieces);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), "");
    }
}
