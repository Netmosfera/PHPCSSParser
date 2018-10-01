<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

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

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid hex digits
 * #3 | test invalid whitespace token length
 */
class CPEscapeTokenTest extends TestCase
{
    public function data1(){
        $digits = str_split("abcdefABCDEF1234567890", 1);

        $d = function() use(&$digits){
            $d = array_shift($digits);
            $digits[] = $d;
            return $d;
        };

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
    public function test1(String $hexDigits, String $ws){
        $ws1 = $ws === "" ? NULL : new CheckedWhitespaceToken($ws);
        $ws2 = $ws === "" ? NULL : new CheckedWhitespaceToken($ws);
        $CPEscape1 = new CheckedCodePointEscapeToken($hexDigits, $ws1);
        $CPEscape2 = new CheckedCodePointEscapeToken($hexDigits, $ws2);

        assertMatch($CPEscape1, $CPEscape2);

        assertMatch("\\" . $hexDigits . $ws, (String)$CPEscape1);
        assertMatch((String)$CPEscape1, (String)$CPEscape2);

        assertMatch($hexDigits, $CPEscape1->hexDigits());
        assertMatch($CPEscape1->hexDigits(), $CPEscape2->hexDigits());

        assertMatch(hexdec($hexDigits), $CPEscape1->integerValue());
        assertMatch($CPEscape1->integerValue(), $CPEscape2->integerValue());

        assertMatch($ws === "" ? NULL : new CheckedWhitespaceToken($ws),
            $CPEscape1->terminator());
        assertMatch($CPEscape1->terminator(), $CPEscape2->terminator());

        $codePoint = IntlChar::chr($CPEscape1->integerValue());

        assertMatch($codePoint ?? "\u{FFFD}", $CPEscape1->intendedValue());
        assertMatch($CPEscape1->intendedValue(), $CPEscape2->intendedValue());

        assertMatch($codePoint !== NULL, $CPEscape1->isValid());
        assertMatch($CPEscape1->isValid(), $CPEscape2->isValid());
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
    public function test3(String $whitespace1, String $whitespace2){
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
