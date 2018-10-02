<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatEscapeTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatBadURLRemnantsToken;
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
    private $remnantsBeginBit = "( begin bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

    private $remnantsBit = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

    private function piecesAfterPiece($afterPiece){
        if($afterPiece === NULL){
            // BadURLRemnants may only start with a non-valid escape or a
            // BadURLRemnantsBit that starts with a character not allowed in a URLToken
            $data[] = new CheckedBadURLRemnantsBitToken($this->remnantsBeginBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
        }elseif($afterPiece instanceof BadURLRemnantsBitToken){
            // BadURLRemnantsBit can *not* appear after another one, only escapes can
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }else{
            assert($afterPiece instanceof EscapeToken);
            // After a EscapeToken can appear anything
            $data[] = new CheckedBadURLRemnantsBitToken($this->remnantsBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }
        return $data;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "piecesAfterPiece"]), FALSE),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, FALSE);

        $traverser = getTraverser($prefix, $badURLRemnants . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "piecesAfterPiece"]), FALSE)
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, Array $pieces){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, TRUE);

        $traverser = getTraverser($prefix, $badURLRemnants . "");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), "");
    }
}
