<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function array_shift;
use IntlChar;
use function iterator_to_array;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedHexEscape;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid hex digits
 */
class HexEscapeTest extends TestCase
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
        $object1 = new CheckedHexEscape($hexDigits, $whitespace === "" ? NULL : new WhitespaceToken($whitespace));
        $object2 = new CheckedHexEscape($hexDigits, $whitespace === "" ? NULL : new WhitespaceToken($whitespace));

        assertMatch($object1, $object2);

        assertMatch($hexDigits, $object1->getHexDigits());
        assertMatch($object1->getHexDigits(), $object2->getHexDigits());

        assertMatch(hexdec($hexDigits), $object1->getIntValue());
        assertMatch($object1->getIntValue(), $object2->getIntValue());

        assertMatch($whitespace === "" ? NULL : new WhitespaceToken($whitespace), $object1->getTerminator());
        assertMatch($object1->getTerminator(), $object2->getTerminator());

        $codePoint = IntlChar::chr($object1->getIntValue());

        assertMatch($codePoint ?? "\u{FFFD}", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());

        assertMatch($codePoint !== NULL, $object1->isValidCodePoint());
        assertMatch($object1->isValidCodePoint(), $object2->isValidCodePoint());

        assertMatch("\\" . $hexDigits . $whitespace, (String)$object1);
        assertMatch((String)$object1, (String)$object2);
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
            new CheckedHexEscape($hexDigits, NULL);
        });
    }
}
