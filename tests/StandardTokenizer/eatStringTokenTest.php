<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
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

    private function piecesAfterPiece($afterPiece){
        if($afterPiece === NULL){
            // StringToken may start with anything
            $data[] = new CheckedStringBitToken($this->stringBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }elseif($afterPiece instanceof StringBitToken){
            // StringBitToken can *not* appear after another one, only escapes can
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }else{
            assert($afterPiece instanceof EscapeToken);
            // After a EscapeToken can appear anything
            $data[] = new CheckedStringBitToken($this->stringBit);
            $data[] = new CheckedContinuationEscapeToken("\n");
            $data[] = new CheckedEncodedCodePointEscapeToken("@");
        }
        return $data;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(Closure::fromCallable([$this, "piecesAfterPiece"])),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $delimiter, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $string = new CheckedStringToken($delimiter, $pieces, FALSE);

        $traverser = getTraverser($prefix, $string . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data2(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(Closure::fromCallable([$this, "piecesAfterPiece"]))
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $delimiter, Array $pieces){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $string = new CheckedStringToken($delimiter, $pieces, TRUE);

        $traverser = getTraverser($prefix, $string . "");
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "");
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\"", "'"],
            makePiecesSample(Closure::fromCallable([$this, "piecesAfterPiece"])),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $delimiter, Array $pieces, String $rest){
        $escape1 = new CheckedContinuationEscapeToken("\n");
        $escape2 = new CheckedEncodedCodePointEscapeToken("@");
        $string = new CheckedBadStringToken($delimiter, $pieces);

        $traverser = getTraverser($prefix, $string . "\f" . $rest);
        $eatEscape = eatEscapeTokenFunction([$escape1, $escape2]);
        $actualString = eatStringToken($traverser, "\f", $eatEscape);

        assertMatch($actualString, $string);
        assertMatch($traverser->eatAll(), "\f" . $rest);
    }
}
