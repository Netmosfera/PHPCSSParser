<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function array_shift;
use function iterator_to_array;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use PHPUnit\Framework\TestCase;
use IntlChar;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid hex digits
 * #3 | test invalid whitespace token length
 */
class CodePointEscapeTokenTest extends TestCase
{
    function data1(){
        $digits = str_split("abcdefABCDEF1234567890", 1);
        $d = function() use(&$digits){ $d = array_shift($digits); $digits[] = $d; return $d; };
        $hex[] = $d();
        $hex[] = $d() . $d();
        $hex[] = $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d() . $d() . $d();
        $hex[] = $d();
        $hex[] = $d() . $d();
        $hex[] = $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d() . $d();
        $hex[] = $d() . $d() . $d() . $d() . $d() . $d();

        $whitespaces = iterator_to_array(getWhitespaceSeqsSet(), FALSE);
        $whitespaces[] = "";
        return cartesianProduct($hex, $whitespaces);
    }

    /** @dataProvider data1 */
    function test1(String $hexDigits, String $whitespace){
        $whitespace1 = $whitespace === "" ? NULL : new CheckedWhitespaceToken($whitespace);
        $whitespace2 = $whitespace === "" ? NULL : new CheckedWhitespaceToken($whitespace);
        $object1 = new CheckedCodePointEscapeToken($hexDigits, $whitespace1);
        $object2 = new CheckedCodePointEscapeToken($hexDigits, $whitespace2);

        assertMatch($object1, $object2);

        assertMatch("\\" . $hexDigits . $whitespace, (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($hexDigits, $object1->getHexDigits());
        assertMatch($object1->getHexDigits(), $object2->getHexDigits());

        assertMatch(hexdec($hexDigits), $object1->getIntValue());
        assertMatch($object1->getIntValue(), $object2->getIntValue());

        assertMatch($whitespace === "" ? NULL : new CheckedWhitespaceToken($whitespace), $object1->getTerminator());
        assertMatch($object1->getTerminator(), $object2->getTerminator());

        $codePoint = IntlChar::chr($object1->getIntValue());

        assertMatch($codePoint ?? "\u{FFFD}", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());

        assertMatch($codePoint !== NULL, $object1->isValidCodePoint());
        assertMatch($object1->isValidCodePoint(), $object2->isValidCodePoint());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        $seqs[] = "";
        $seqs[] = "G";
        $seqs[] = "gg";
        $seqs[] = "gGg";
        $seqs[] = "gGGg";
        $seqs[] = "ggGgg";
        $seqs[] = "ggGGgg";
        $seqs[] = "ffffffF";
        return cartesianProduct($seqs);
    }

    /** @dataProvider data2 */
    function test2(String $hexDigits){
        assertThrowsType(InvalidToken::CLASS, function() use($hexDigits){
            new CheckedCodePointEscapeToken($hexDigits, NULL);
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(getWhitespaceSeqsSet(), getWhitespaceSeqsSet());
    }

    /** @dataProvider data3 */
    function test3(String $whitespace1, String $whitespace2){
        $whitespaces = $whitespace1 . $whitespace2;
        if($whitespaces === "\r\n"){
            $whitespaces = $whitespaces . $whitespaces;
        }
        $whitespace = new CheckedWhitespaceToken($whitespaces);
        assertThrowsType(InvalidToken::CLASS, function() use($whitespace){
            new CheckedCodePointEscapeToken("FF", $whitespace);
        });
    }
}
