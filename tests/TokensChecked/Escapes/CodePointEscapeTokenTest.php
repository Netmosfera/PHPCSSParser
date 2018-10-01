<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

use function array_shift;
use function iterator_to_array;
use Netmosfera\PHPCSSAST\SpecData;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;
use IntlChar;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid hex digits
 * #3 | test invalid whitespace token length
 */
class CodePointEscapeTokenTest extends TestCase
{
    public function data1(){
        $possibleDigits = str_split("AaBbCcDdEeFf1234567890", 1);

        $digit = function() use(&$possibleDigits){
            $digit = array_shift($possibleDigits);
            $possibleDigits[] = $digit;
            return $digit;
        };

        $hexDigits[] = $digit();
        $hexDigits[] = $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit();
        $hexDigits[] = $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit() . $digit();

        $whitespaces = iterator_to_array(getWhitespaceSeqsSet(), FALSE);
        $whitespaces[] = NULL;

        return cartesianProduct($hexDigits, $whitespaces);
    }

    /** @dataProvider data1 */
    public function test1(String $hexDigits, ?String $ws){
        $ws1 = $ws === NULL ? NULL : new CheckedWhitespaceToken($ws);
        $ws2 = $ws === NULL ? NULL : new CheckedWhitespaceToken($ws);
        $escape1 = new CheckedCodePointEscapeToken($hexDigits, $ws1);
        $escape2 = new CheckedCodePointEscapeToken($hexDigits, $ws2);
        assertMatch($escape1, $escape2);
        $codePoint = IntlChar::chr($escape1->integerValue());
        $intendedValue = $codePoint ?? SpecData::REPLACEMENT_CHARACTER;
        $isValid = $codePoint !== NULL;
        assertMatch((String)$escape1, "\\" . $hexDigits . $ws);
        assertMatch($escape1->hexDigits(), $hexDigits);
        assertMatch($escape1->integerValue(), hexdec($hexDigits));
        assertMatch($escape1->terminator(), $ws2);
        assertMatch($escape1->intendedValue(), $intendedValue);
        assertMatch($escape1->isValid(), $isValid);
    }

    public function data2(){
        $seqs[] = "";
        $seqs[] = "G";
        $seqs[] = "gg";
        $seqs[] = "gGg";
        $seqs[] = "gGGg";
        $seqs[] = "ggGgg";
        $seqs[] = "ggGGgg";
        $seqs[] = "ffffffF";
        $seqs[] = "1111112";
        return cartesianProduct($seqs);
    }

    /** @dataProvider data2 */
    public function test2(String $hexDigits){
        assertThrowsType(InvalidToken::CLASS, function() use($hexDigits){
            new CheckedCodePointEscapeToken($hexDigits, NULL);
        });
    }

    public function data3(){
        return cartesianProduct(getWhitespaceSeqsSet(), getWhitespaceSeqsSet());
    }

    /** @dataProvider data3 */
    public function test3(String $ws1, String $ws2){
        $whitespaces = $ws1 . $ws2;
        if($whitespaces === "\r\n"){
            $whitespaces = $whitespaces . $whitespaces;
        }
        $whitespace = new CheckedWhitespaceToken($whitespaces);
        assertThrowsType(InvalidToken::CLASS, function() use($whitespace){
            new CheckedCodePointEscapeToken("FF", $whitespace);
        });
    }
}
