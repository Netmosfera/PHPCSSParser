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
    private function piecesAfter($afterPiece){
        if($afterPiece === NULL){
            // BadURLRemnants may only start with a non-valid escape or a
            // BadURLRemnantsBit that starts with a character not allowed in a URLToken
            $data[] = new CheckedBadURLRemnantsBitToken($this->remnantsBegin);
            $data[] = new CheckedContinuationEscapeToken("\n");
        }elseif($afterPiece instanceof BadURLRemnantsBitToken){
            // After a BadURLRemnantsBit can *not* appear another one, but only escapes
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }else{
            assert($afterPiece instanceof EscapeToken);
            // After a EscapeToken can appear anything
            $data[] = new CheckedBadURLRemnantsBitToken($this->remnants);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }
        return $data;
    }

    private $remnantsBegin = "( begin bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";
    private $remnants = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

    //------------------------------------------------------------------------------------

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "piecesAfter"]), FALSE),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test1(String $prefix, Array $pieces, String $rest){
        $traverser = getTraverser($prefix, implode("", $pieces) . ")" . $rest);
        $expected = new CheckedBadURLRemnantsToken($pieces, FALSE);
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample(Closure::fromCallable([$this, "piecesAfter"]), FALSE)
        );
    }

    /** @dataProvider data4 */
    public function test2(String $prefix, Array $pieces){
        $traverser = getTraverser($prefix, implode("", $pieces));
        $expected = new CheckedBadURLRemnantsToken($pieces, TRUE);
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actual = eatBadURLRemnantsToken($traverser, $eatEscape);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }
}
