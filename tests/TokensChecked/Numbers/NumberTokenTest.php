<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;

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
 *
 * #20 test conversion to float and number
 */
class NumberTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $sign){
        $number1 = new CheckedNumberToken($sign, "123", "", "", "", "");
        $number2 = new CheckedNumberToken($sign, "123", "", "", "", "");
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . "123");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "123");
        assertMatch($number1->decimals(), "");
        assertMatch($number1->EIndicator(), "");
        assertMatch($number1->ESign(), "");
        assertMatch($number1->EExponent(), "");
    }

    public function data2(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data2 */
    public function test2(String $sign){
        $number1 = new CheckedNumberToken($sign, "", "456", "", "", "");
        $number2 = new CheckedNumberToken($sign, "", "456", "", "", "");
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . ".456");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "");
        assertMatch($number1->decimals(), "456");
        assertMatch($number1->EIndicator(), "");
        assertMatch($number1->ESign(), "");
        assertMatch($number1->EExponent(), "");
    }

    public function data3(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data3 */
    public function test3(String $sign){
        $number1 = new CheckedNumberToken($sign, "123", "456", "", "", "");
        $number2 = new CheckedNumberToken($sign, "123", "456", "", "", "");
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . "123.456");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "123");
        assertMatch($number1->decimals(), "456");
        assertMatch($number1->EIndicator(), "");
        assertMatch($number1->ESign(), "");
        assertMatch($number1->EExponent(), "");
    }

    public function data4(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data4 */
    public function test4(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");
        $number2 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . "123" . $e . $eSign . "7");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "123");
        assertMatch($number1->decimals(), "");
        assertMatch($number1->EIndicator(), $e);
        assertMatch($number1->ESign(), $eSign);
        assertMatch($number1->EExponent(), "7");
    }

    public function data5(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data5 */
    public function test5(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");
        $number2 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . ".456" . $e . $eSign . "789");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "");
        assertMatch($number1->decimals(), "456");
        assertMatch($number1->EIndicator(), $e);
        assertMatch($number1->ESign(), $eSign);
        assertMatch($number1->EExponent(), "789");
    }

    public function data6(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data6 */
    public function test6(String $sign, String $e, String $eSign){
        $number1 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789");
        $number2 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789" );
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . "123.456" . $e . $eSign . "789");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "123");
        assertMatch($number1->decimals(), "456");
        assertMatch($number1->EIndicator(), $e);
        assertMatch($number1->ESign(), $eSign);
        assertMatch($number1->EExponent(), "789");
    }

    public function data7(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-", ""]);
    }

    /** @dataProvider data7 */
    public function test7(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "", "", $eLetter, $eSign, "5");
        });
    }

    public function data8(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-", ""]);
    }

    /** @dataProvider data8 */
    public function test8(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "11", "22", $eLetter, $eSign, "");
        });
    }

    public function data9(){
        return cartesianProduct(["+", "-", ""], ["+", "-", ""]);
    }

    /** @dataProvider data9 */
    public function test9(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "11", "22", "", $eSign, "4");
        });
    }

    public function data10(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data10 */
    public function test10(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "5");
        });
    }

    public function data11(){
        return cartesianProduct(["+", "-", ""], ["e", "E"], ["+", "-"]);
    }

    /** @dataProvider data11 */
    public function test11(String $sign, String $eLetter, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eLetter, $eSign){
            new CheckedNumberToken($sign, "123", "", $eLetter, $eSign, "");
        });
    }

    public function data12(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data12 */
    public function test12(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "5");
        });
    }

    public function data13(){
        return cartesianProduct(["+", "-", ""], ["+", "-"]);
    }

    /** @dataProvider data13 */
    public function test13(String $sign, String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign, $eSign){
            new CheckedNumberToken($sign, "123", "", "", $eSign, "");
        });
    }

    public function data14(){
        return cartesianProduct(["++", "--", "*"]);
    }

    /** @dataProvider data14 */
    public function test14(String $sign){
        assertThrowsType(InvalidToken::CLASS, function() use($sign){
            new CheckedNumberToken($sign, "123", "", "", "", "");
        });
    }

    public function data15(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data15 */
    public function test15(String $wholes){
        assertThrowsType(InvalidToken::CLASS, function() use($wholes){
            new CheckedNumberToken("", $wholes, "", "", "", "");
        });
    }

    public function data16(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data16 */
    public function test16(String $decimals){
        assertThrowsType(InvalidToken::CLASS, function() use($decimals){
            new CheckedNumberToken("", "", $decimals, "", "", "");
        });
    }

    public function data17(){
        return cartesianProduct(["ee", "EE", "eE", "Ee", "a", "0"]);
    }

    /** @dataProvider data17 */
    public function test17(String $eLetter){
        assertThrowsType(InvalidToken::CLASS, function() use($eLetter){
            new CheckedNumberToken("", "123", "", $eLetter, "", "");
        });
    }

    public function data18(){
        return cartesianProduct(["++", "--", "*"]);
    }

    /** @dataProvider data18 */
    public function test18(String $eSign){
        assertThrowsType(InvalidToken::CLASS, function() use($eSign){
            new CheckedNumberToken("", "123", "", "e", $eSign, "123");
        });
    }

    public function data19(){
        return cartesianProduct(["", "123a", "a123", "aaa"]);
    }

    /** @dataProvider data19 */
    public function test19(String $eExponent){
        assertThrowsType(InvalidToken::CLASS, function() use($eExponent){
            new CheckedNumberToken("", "123", "", "e", "", $eExponent);
        });
    }

    public function test20(){
        $number = new CheckedNumberToken("+", "12345", "6789", "e", "+", "42");
        assertMatch($number->floatValue(), 12345.6789e42);
        assertMatch($number->numberValue(), 12345.6789e42);
        $number = new CheckedNumberToken("+", "123", "456", "e", "+", "4");
        assertMatch($number->floatValue(), 123.456e4);
        assertMatch($number->numberValue(), 1234560);
        $number = new CheckedNumberToken("+", "0", "0", "e", "+", "42");
        assertMatch($number->floatValue(), 0.0);
        assertMatch($number->numberValue(), 0);
    }
}
