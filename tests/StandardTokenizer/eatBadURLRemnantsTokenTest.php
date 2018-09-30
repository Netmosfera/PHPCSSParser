<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatBadURLRemnantsToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1  | immediate EOF // @TODO remove not possible anymore as it requires actual valid data
 * #2  | immediate )   // @TODO remove not possible anymore
 * #3  | immediate non-escape
 * #4  | immediate escape
 * #5  | loop terminated
 * #6  | loop unterminated (EOF)
  */
class eatBadURLRemnantsTokenTest extends TestCase
{
    public function data3(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $rest){
        $traverser = getTraverser($prefix, $this->remnants . ")" . $rest);
        $badURLRemnantsBit = new CheckedBadURLRemnantsBitToken($this->remnants);
        $expected = new CheckedBadURLRemnantsToken([$badURLRemnantsBit], FALSE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            return NULL;
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $rest){
        $traverser = getTraverser($prefix, "\\\n" . ")" . $rest);
        $continuationEscape = new CheckedContinuationEscapeToken("\n");
        $expected = new CheckedBadURLRemnantsToken([$continuationEscape], FALSE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            assertNotMatch($traverser->eatStr("\\\n"), NULL);
            return new CheckedContinuationEscapeToken("\n");
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data56(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "getPieces"]), FALSE),
            ANY_UTF8()
        );
    }

    /** @dataProvider data56 */
    public function test5(String $prefix, Array $pieces, String $rest){
        $traverser = getTraverser($prefix, implode("", $pieces) . ")" . $rest);
        $expected = new CheckedBadURLRemnantsToken($pieces, FALSE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            $escape = $traverser->eatStr("\\\n");
            return $escape === NULL ? NULL : new CheckedContinuationEscapeToken("\n");
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    /** @dataProvider data56 */
    public function test6(String $prefix, Array $pieces){
        $traverser = getTraverser($prefix, implode("", $pieces));
        $expected = new CheckedBadURLRemnantsToken($pieces, TRUE);
        $eatEscape = function(Traverser $traverser): ?EscapeToken{
            $escape = $traverser->eatStr("\\\n");
            return $escape === NULL ? NULL : new CheckedContinuationEscapeToken("\n");
        };
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    public function getPieces($afterPiece){
        if(!$afterPiece instanceof BadURLRemnantsBitToken){
            $data[] = new CheckedBadURLRemnantsBitToken($this->remnants);
        }
        $data[] = new CheckedContinuationEscapeToken("\n");
        return $data;
    }

    private $remnants = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";
}
