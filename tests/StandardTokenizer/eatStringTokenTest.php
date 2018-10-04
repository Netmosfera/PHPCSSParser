<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTTests\StandardTokenizer\Fakes\eatEscapeTokenFunction;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatStringToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | good string
 * #2 | good string terminated with eof
 * #3 | bad string
  */
class eatStringTokenTest extends TestCase
{
    private $stringBit = "string \u{2764} bit";

    private function piecesAfterPiece(Bool $EOFTerminated){
        return function($afterPiece, Bool $isLast) use($EOFTerminated){
            $data = [];
            if($afterPiece === NULL){
                $data[] = new CheckedStringBitToken($this->stringBit);
                $data[] = new CheckedContinuationEscapeToken("\n");
                $data[] = new CheckedEncodedCodePointEscapeToken("@");
                $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
                if($EOFTerminated && $isLast){
                    $data[] = new CheckedEOFEscapeToken();
                }
            }elseif($afterPiece instanceof StringBitToken){
                $data[] = new CheckedContinuationEscapeToken("\n");
                $data[] = new CheckedEncodedCodePointEscapeToken("@");
                $data[] = new CheckedCodePointEscapeToken("Fac", NULL);
                if($EOFTerminated && $isLast){
                    $data[] = new CheckedEOFEscapeToken();
                }
            }elseif($afterPiece instanceof EscapeToken){
                $data[] = new CheckedStringBitToken($this->stringBit);
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
            ["\"", "'"],
            makePiecesSample($this->piecesAfterPiece(FALSE)),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $delimiter, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $escape3 = new CheckedCodePointEscapeToken("Fac", NULL);
        $string = new CheckedStringToken($delimiter, $pieces, FALSE);

        $traverser = getTraverser($prefix, $string . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2, $escape3]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample($this->piecesAfterPiece(TRUE))
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $delimiter, Array $pieces){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $escape3 = new CheckedCodePointEscapeToken("Fac", NULL);
        $escape4 = new EOFEscapeToken();
        $string = new CheckedStringToken($delimiter, $pieces, TRUE);

        $traverser = getTraverser($prefix, $string . "");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2, $escape3, $escape4]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample($this->piecesAfterPiece(FALSE)),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $delimiter, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $escape3 = new CheckedCodePointEscapeToken("Fac", NULL);
        $string = new CheckedBadStringToken($delimiter, $pieces);

        $traverser = getTraverser($prefix, $string . "\f" . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2, $escape3]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }
}
