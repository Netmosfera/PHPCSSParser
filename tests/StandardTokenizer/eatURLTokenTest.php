<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertNotMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLRemnantsToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedBadURLToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\URLs\CheckedURLToken;
use Netmosfera\PHPCSSAST\StandardTokenizer\Traverser;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * @TODO improve these
 *
 * #0  |                NULL if starts with whitespace* followed by string delimiter
 *
 * #1  |                EOF
 * #2  |                ) + rest
 * #3  |                invalid escape -> remnants
 * #4  |                valid escape
 * #5  |                blacklisted code point -> remnants
 * #6  |                sequence
 *
 * #7  |     sequence + EOF
 * #8  |     sequence + ) + rest
 * #9  |     sequence + invalid escape -> remnants
 * #10 |     sequence + valid escape
 * #11 |     sequence + blacklisted code point -> remnants
 * #12 |     sequence + ws + not ) or EOF
 */
class eatURLTokenTest extends TestCase
{
    public function data0(){
        return cartesianProduct(
            ANY_UTF8(),
            ["\f\f\f", "\f\f", "\f", ""],
            ["\"", "'"],
            ANY_UTF8()
        );
    }

    /** @dataProvider data0 */
    public function test0(
        String $prefix,
        String $startWS,
        String $stringDelimiter,
        String $rest
    ){
        $traverser = getTraverser($prefix, $startWS . $stringDelimiter . $rest);
        $expected = NULL;
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $actual = eatURLToken($traverser, $URLID, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $startWS . $stringDelimiter . $rest);
    }

    public function data1(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $startWS){
        $traverser = getTraverser($prefix, $startWS);
        $startWS = $startWS === "" ? NULL : new CheckedWhitespaceToken($startWS);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedURLToken($URLID, $startWS, [], NULL, TRUE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $actual = eatURLToken($traverser, $URLID, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""], ANY_UTF8());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $startWS, String $rest){
        $traverser = getTraverser($prefix, $startWS . ")" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedURLToken($URLID, $startWS, [], NULL, FALSE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $actual = eatURLToken($traverser, $URLID, "\f", "", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(ANY_UTF8(), ["\f\f\f", "\f\f", "\f", ""], ANY_UTF8());
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $startWS, String $rest){
        $traverser = getTraverser($prefix, $startWS . "\\\n" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $remnantsBit = new CheckedBadURLRemnantsBitToken("(irrelevant");
        $remnants = new CheckedBadURLRemnantsToken([$remnantsBit], FALSE);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedBadURLToken($URLID, $startWS, [], $remnants);
        $eatEscape = function(Traverser $traverser){
            assertNotMatch($traverser->createBranch()->eatStr("\\\n"), NULL);
            return NULL;
        };
        $eatRemnants = function(Traverser $traverser) use($remnants){
            assertNotMatch($traverser->eatStr("\\\n"), NULL);
            return $remnants;
        };
        $actual = eatURLToken($traverser, $URLID, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $startWS, String $rest){
        $ve = "\\66ff";
        $traverser = getTraverser($prefix, $startWS . $ve . ")" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $escape = new CheckedCodePointEscapeToken("66ff", NULL);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedURLToken($URLID, $startWS, [$escape], NULL, FALSE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser) use($escape, $ve){
            assertNotMatch($traverser->eatStr($ve), NULL);
            return $escape;
        };
        $actual = eatURLToken($traverser, $URLID, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data5(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $startWS, String $rest){
        $ic = "6 invalid code point";
        $traverser = getTraverser($prefix, $startWS . $ic . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $remnantsBit = new CheckedBadURLRemnantsBitToken("(irrelevant");
        $remnants = new CheckedBadURLRemnantsToken([$remnantsBit], FALSE);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedBadURLToken($URLID, $startWS, [], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ic){
            assertNotMatch($traverser->eatStr($ic), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $actual = eatURLToken($traverser, $URLID, "\f", "0-9", $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data6(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data6 */
    public function test6(String $prefix, String $startWS, String $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . ")" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $URLBit = new CheckedURLBitToken($vs);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedURLToken($URLID, $startWS, [$URLBit], NULL, FALSE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data7(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""]);
    }

    /** @dataProvider data7 */
    public function test7(String $prefix, String $startWS, String $endWS){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . $endWS);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $endWS = $endWS === "" ? NULL :new CheckedWhitespaceToken($endWS);
        $URLBit = new CheckedURLBitToken($vs);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));

        $expected = new CheckedURLToken($URLID, $startWS, [$URLBit], $endWS, TRUE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), "");
    }

    public function data8(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data8 */
    public function test8(String $prefix, String $startWS, String $endWS, String $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . $endWS . ")" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $endWS = $endWS === "" ? NULL :new CheckedWhitespaceToken($endWS);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));

        $expected = new CheckedURLToken($URLID, $startWS, [new CheckedURLBitToken($vs)],
            $endWS, FALSE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data9(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data9 */
    public function test9(String $prefix, String $startWS, String $rest){
        $vs = "valid sequence";
        $ie = "\\\n invalid escape";
        $traverser = getTraverser($prefix, $startWS . $vs . $ie . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $remnants = new CheckedBadURLRemnantsToken(
            [new CheckedBadURLRemnantsBitToken("(irrelevant")], FALSE);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));


        $expected = new CheckedBadURLToken($URLID, $startWS,
            [new CheckedURLBitToken($vs)], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ie){
            assertNotMatch($traverser->eatStr($ie), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser) use($ie){
            assertNotMatch($traverser->createBranch()->eatStr($ie), NULL);
            return NULL;
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data10(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data10 */
    public function test10(String $prefix, String $startWS, String $rest){
        $vs = "valid sequence";
        $ve = "\\66ff";
        $traverser = getTraverser($prefix, $startWS . $vs . $ve . ")" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $escape = new CheckedCodePointEscapeToken("FFaaCC", NULL);
        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));

        $expected = new CheckedURLToken( $URLID,
            $startWS, [new CheckedURLBitToken($vs), $escape], NULL, FALSE);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = function(Traverser $traverser) use($escape, $ve){
            assertNotMatch($traverser->eatStr($ve), NULL);
            return $escape;
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data11(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data11 */
    public function test11(String $prefix, String $startWS, String $rest){
        $vs = "valid sequence";
        $ic = "6 invalid code point";
        $traverser = getTraverser($prefix, $startWS . $vs . $ic . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $remnants = new CheckedBadURLRemnantsToken(
            [new CheckedBadURLRemnantsBitToken("(irrelevant")], FALSE);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));

        $expected = new CheckedBadURLToken($URLID, $startWS,
            [new CheckedURLBitToken($vs)], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants, $ic){
            assertNotMatch($traverser->eatStr($ic), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data12(){
        return cartesianProduct(ANY_UTF8(), ["\f", ""], ANY_UTF8());
    }

    /** @dataProvider data12 */
    public function test12(String $prefix, String $startWS, String $rest){
        $vs = "valid sequence";
        $traverser = getTraverser($prefix, $startWS . $vs . "\f remnants" . $rest);
        $startWS = $startWS === "" ? NULL :new CheckedWhitespaceToken($startWS);
        $remnants = new CheckedBadURLRemnantsToken(
            [new CheckedBadURLRemnantsBitToken("(irrelevant")], FALSE);

        $URLID = new CheckedIdentifierToken(new CheckedNameToken(
            [new CheckedNameBitToken("url")]));
        $expected = new CheckedBadURLToken($URLID,
            $startWS, [new CheckedURLBitToken($vs)], $remnants);
        $eatRemnants = function(Traverser $traverser) use($remnants){
            assertNotMatch($traverser->eatStr("\f remnants"), NULL);
            return $remnants;
        };
        $eatEscape = function(Traverser $traverser){
            self::fail();
        };
        $disallowedChars = "0-9" . preg_quote("()\\\"'\f");
        $actual = eatURLToken($traverser, $URLID, "\f", $disallowedChars,
            $eatEscape, $eatRemnants);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data24(){
        return cartesianProduct(
            ANY_UTF8(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data24 */
    public function test24(String $prefix, String $rest){
        $startWS = new CheckedWhitespaceToken("\f");
        $endWS = new CheckedWhitespaceToken("\f");
        $pieces[] = new CheckedURLBitToken("string1");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("x");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("y");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("z");
        $pieces[] = new CheckedURLBitToken("string2");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("#");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("+");
        $pieces[] = new CheckedURLBitToken("string3");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("@");
        $pieces[] = new CheckedURLBitToken("string4");
        $pieces[] = $escapes[] = new CheckedEncodedCodePointEscapeToken("#");
        $pieces[] = new CheckedURLBitToken("string5");
        $URL = implode("", $pieces);
        $traverser = getTraverser(
            $prefix, $startWS . $URL . $endWS . ")" . $rest);
        $eatRemnants = function(Traverser $traverser){
            self::fail();
        };
        $eatEscape = makeEatEscapeFunctionFromEscapeList($escapes);
        $disallowedChars = "@" . preg_quote("()\\\"'\f");
        $URLID = new CheckedIdentifierToken(new CheckedNameToken([new CheckedNameBitToken("url")]));
        $expected = eatURLToken(
            $traverser, $URLID, "\f", $disallowedChars, $eatEscape, $eatRemnants);
        $actual = new CheckedURLToken($URLID, $startWS, $pieces, $endWS, FALSE);
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }
}
