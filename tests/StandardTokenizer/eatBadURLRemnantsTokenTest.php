<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
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
    private $remnantsBeginBit = "( start bad {} \u{2764} \" URL \u{2764} ' remnants url(";

    private $remnantsBit = "bad { } \u{2764} \" URL \u{2764} ' remnants ( url(";

    private function piecesAfterPiece(Bool $EOFTerminated){
        return function($afterPiece, Bool $isLast) use($EOFTerminated){
            $data = [];
            if($afterPiece === NULL){
                $data[] = new CheckedBadURLRemnantsBitToken($this->remnantsBeginBit);
                $data[] = new CheckedContinuationEscapeToken("\n");
                if($EOFTerminated && $isLast){
                    $data[] = new CheckedEOFEscapeToken();
                }
            }elseif($afterPiece instanceof BadURLRemnantsBitToken){
                $data[] = new CheckedContinuationEscapeToken("\n");
                $data[] = new CheckedEncodedCodePointEscapeToken("@");
                $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
                if($EOFTerminated && $isLast){
                    $data[] = new CheckedEOFEscapeToken();
                }
            }elseif($afterPiece instanceof EscapeToken){
                $data[] = new CheckedBadURLRemnantsBitToken($this->remnantsBit);
                $data[] = new CheckedContinuationEscapeToken("\n");
                $data[] = new CheckedEncodedCodePointEscapeToken("@");
                $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
                if($EOFTerminated && $isLast){
                    $data[] = new CheckedEOFEscapeToken();
                }
            }
            return $data;
        };
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample($this->piecesAfterPiece(FALSE), FALSE),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $escape3 = new CheckedCodePointEscapeToken("Fac", NULL);
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, FALSE);

        $traverser = getTraverser($prefix, $badURLRemnants . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2, $escape3]);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            makePiecesSample($this->piecesAfterPiece(TRUE), FALSE)
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, Array $pieces){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $escape3 = new CheckedCodePointEscapeToken("Fac", NULL);
        $escape4 = new EOFEscapeToken();
        $EOFTerminated = end($pieces) instanceof EOFEscapeToken;
        $badURLRemnants = new CheckedBadURLRemnantsToken($pieces, $EOFTerminated);

        $traverser = getTraverser($prefix, $badURLRemnants . "");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2, $escape3, $escape4]);
        $actualBadURLRemnants = eatBadURLRemnantsToken($traverser, $eatEscape);

        assertMatch($actualBadURLRemnants, $badURLRemnants);
        assertMatch($traverser->eatAll(), "");
    }
}
