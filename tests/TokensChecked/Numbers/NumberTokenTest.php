<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1  | test getters only wholes
 * #2  | test getters only decimals
 * #3  | test getters wholes and decimals
 * #4  | test getters only wholes and e-part
 * #5  | test getters only decimals and e-part
 * #6  | test getters wholes and decimals and e-part
 *
 * missing components:
 *     | number | e-letter | e-sign | e-exponent
 *  #7 |   no   |   ?      |    ?   |     ?
 *  #8 |    ?   |  yes     |    ?   |    no
 *  #9 |    ?   |   no     |    ?   |    yes
 * #10 |    ?   |   no     |    ?   |    yes
 * #11 |    ?   |   ?      |  yes   |    no
 * #12 |    ?   |   no     |  yes   |    ?
 * #13 |    ?   |   no     |  yes   |    no
 *
 * #14 invalid sign
 * #15 invalid wholes
 * #16 invalid decimals
 * #17 invalid e-letter
 * #18 invalid e-sign
 * #19 invalid e-exponent
 */
class NumberTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data1 */
    function test1(String $sign){
        $number1 = new CheckedNumberToken($sign, "123", "", "", "", "");
        $number2 = new CheckedNumberToken($sign, "123", "", "", "", "");

        assertMatch($number1, $number2);

        assertMatch($sign . "123", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("123", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch("", $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch("", $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data2 */
    function test2(String $sign){
        $number1 = new CheckedNumberToken($sign, "", "456", "", "", "");
        $number2 = new CheckedNumberToken($sign, "", "456", "", "", "");

        assertMatch($number1, $number2);

        assertMatch($sign . ".456", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("456", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch("", $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch("", $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data3 */
    function test3(String $sign){
        $number1 = new CheckedNumberToken($sign, "123", "456", "", "", "");
        $number2 = new CheckedNumberToken($sign, "123", "456", "", "", "");

        assertMatch($number1, $number2);

        assertMatch($sign . "123.456", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("123", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("456", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch("", $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch("", $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data4 */
    function test4(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");
        $number2 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");

        assertMatch($number1, $number2);

        assertMatch($sign . "123" . $e . $eSign . "7", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("123", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch($e, $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch($eSign, $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("7", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data5(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data5 */
    function test5(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");
        $number2 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");

        assertMatch($number1, $number2);

        assertMatch($sign . ".456" . $e . $eSign . "789", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("456", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch($e, $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch($eSign, $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("789", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data6(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data6 */
    function test6(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789");
        $number2 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789");

        assertMatch($number1, $number2);

        assertMatch($sign . "123.456" . $e . $eSign . "789", (String)$number1);
        assertMatch((String)$number1, (String)$number2);

        assertMatch($sign, $number1->getSign());
        assertMatch($number1->getSign(), $number2->getSign());

        assertMatch("123", $number1->getWholes());
        assertMatch($number1->getWholes(), $number2->getWholes());

        assertMatch("456", $number1->getDecimals());
        assertMatch($number1->getDecimals(), $number2->getDecimals());

        assertMatch($e, $number1->getEIndicator());
        assertMatch($number1->getEIndicator(), $number2->getEIndicator());

        assertMatch($eSign, $number1->getESign());
        assertMatch($number1->getESign(), $number2->getESign());

        assertMatch("789", $number1->getEExponent());
        assertMatch($number1->getEExponent(), $number2->getEExponent());

        assertMatch($number1->toFloat(), $number2->toFloat());

        assertMatch($number1->toNumber(), $number2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data7(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-", ""]);
    }

    /** @dataProvider data7 */
    function test7(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "", "", $eLetter, $eSign, "5");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data8(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-", ""]);
    }

    /** @dataProvider data8 */
    function test8(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "11", "22", $eLetter, $eSign, "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data9(){
        return cartesianProduct(["+", "-", ""], ["+", "-", ""]);
    }

    /** @dataProvider data9 */
    function test9(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "11", "22", "", $eSign, "4");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data10(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data10 */
    function test10(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "5");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data11(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-"]);
    }

    /** @dataProvider data11 */
    function test11(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "123", "", $eLetter, $eSign, "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data12(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data12 */
    function test12(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "5");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data13(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data13 */
    function test13(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "");
        });
    }

    function data14(){
        return cartesianProduct(["++", "--", "*"]);
    }

    /** @dataProvider data14 */
    function test14(String $sign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign){
            new CheckedNumberToken($sign, "123", "", "", "", "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data15(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data15 */
    function test15(String $wholes){
        assertThrowsType(InvalidToken::CLASS, function() use($wholes){
            new CheckedNumberToken("", $wholes, "", "", "", "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data16(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data16 */
    function test16(String $decimals){
        assertThrowsType(InvalidToken::CLASS, function() use($decimals){
            new CheckedNumberToken("", "", $decimals, "", "", "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data17(){
        return cartesianProduct(["ee", "EE", "eE", "Ee", "a", "0"]);
    }

    /** @dataProvider data17 */
    function test17(String $eLetter){
        assertThrowsType(InvalidToken::CLASS, function() use($eLetter){
            new CheckedNumberToken("", "123", "", $eLetter, "", "");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data18(){
        return cartesianProduct(["++", "--", "*"]);
    }

    /** @dataProvider data18 */
    function test18(String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($eSign){
            new CheckedNumberToken("", "123", "", "e", $eSign, "123");
        });
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data19(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data19 */
    function test19(String $eExponent){
        assertThrowsType(InvalidToken::CLASS, function() use($eExponent){
            new CheckedNumberToken("", "123", "", "e", "", $eExponent);
        });
    }
}
