<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Numbers;

use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;

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
 * #20 test conversion to float and number
 */
class NumberTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data1 */
    public function test1(String $sign){
        $number1 = new NumberToken($sign, "123", "", "", "", "");
        $number2 = new NumberToken($sign, "123", "", "", "", "");
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
        $number1 = new NumberToken($sign, "", "456", "", "", "");
        $number2 = new NumberToken($sign, "", "456", "", "", "");
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
        $number1 = new NumberToken($sign, "123", "456", "", "", "");
        $number2 = new NumberToken($sign, "123", "456", "", "", "");
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
        $number1 = new NumberToken($sign, "123", "", $e, $eSign, "7");
        $number2 = new NumberToken($sign, "123", "", $e, $eSign, "7");
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
        $number1 = new NumberToken($sign, "", "456", $e, $eSign, "789");
        $number2 = new NumberToken($sign, "", "456", $e, $eSign, "789");
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
        $number1 = new NumberToken($sign, "123", "456", $e, $eSign, "789");
        $number2 = new NumberToken($sign, "123", "456", $e, $eSign, "789" );
        assertMatch($number1, $number2);
        assertMatch((String)$number1, $sign . "123.456" . $e . $eSign . "789");
        assertMatch($number1->sign(), $sign);
        assertMatch($number1->wholes(), "123");
        assertMatch($number1->decimals(), "456");
        assertMatch($number1->EIndicator(), $e);
        assertMatch($number1->ESign(), $eSign);
        assertMatch($number1->EExponent(), "789");
    }

    public function test20(){
        $number = new NumberToken("+", "12345", "6789", "e", "+", "42");
        assertMatch($number->floatValue(), 12345.6789e42);
        assertMatch($number->numberValue(), 12345.6789e42);
        $number = new NumberToken("+", "123", "456", "e", "+", "4");
        assertMatch($number->floatValue(), 123.456e4);
        assertMatch($number->numberValue(), 1234560);
        $number = new NumberToken("+", "0", "0", "e", "+", "42");
        assertMatch($number->floatValue(), 0.0);
        assertMatch($number->numberValue(), 0);
    }
}
