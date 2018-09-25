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
 *     | number | e-letter | e-sign | e-exponent
 *  #7 |   no   |   ?      |    ?   |     ?
 *  #8 |    ?   |  yes     |    ?   |    no
 *  #9 |    ?   |   no     |    ?   |    yes
 * #10 |    ?   |   no     |    ?   |    yes
 * #11 |    ?   |   ?      |  yes   |    no
 * #12 |    ?   |   no     |  yes   |    ?
 * #13 |    ?   |   no     |  yes   |    no
 */
class NumberTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data1 */
    function test1(String $sign){
        $object1 = new CheckedNumberToken($sign, "123", "", "", "", "");
        $object2 = new CheckedNumberToken($sign, "123", "", "", "", "");

        assertMatch($object1, $object2);

        assertMatch($sign . "123", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("123", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch("", $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch("", $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data2 */
    function test2(String $sign){
        $object1 = new CheckedNumberToken($sign, "", "456", "", "", "");
        $object2 = new CheckedNumberToken($sign, "", "456", "", "", "");

        assertMatch($object1, $object2);

        assertMatch($sign . ".456", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("456", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch("", $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch("", $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data3(){
        return cartesianProduct(["-", "+", ""]);
    }

    /** @dataProvider data3 */
    function test3(String $sign){
        $object1 = new CheckedNumberToken($sign, "123", "456", "", "", "");
        $object2 = new CheckedNumberToken($sign, "123", "456", "", "", "");

        assertMatch($object1, $object2);

        assertMatch($sign . "123.456", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("123", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("456", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch("", $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch("", $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data4 */
    function test4(String $sign, String $e, String $eSign){
        $object1 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");
        $object2 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "7");

        assertMatch($object1, $object2);

        assertMatch($sign . "123" . $e . $eSign . "7", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("123", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch($e, $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch($eSign, $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("7", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data5(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data5 */
    function test5(String $sign, String $e, String $eSign){
        $object1 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");
        $object2 = new CheckedNumberToken($sign, "", "456", $e, $eSign, "789");

        assertMatch($object1, $object2);

        assertMatch($sign . ".456" . $e . $eSign . "789", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("456", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch($e, $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch($eSign, $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("789", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data6(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data6 */
    function test6(String $sign, String $e, String $eSign){
        $object1 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789");
        $object2 = new CheckedNumberToken($sign, "123", "456", $e, $eSign, "789");

        assertMatch($object1, $object2);

        assertMatch($sign . "123.456" . $e . $eSign . "789", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($sign, $object1->getSign());
        assertMatch($object1->getSign(), $object2->getSign());

        assertMatch("123", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("456", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch($e, $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());

        assertMatch($eSign, $object1->getESign());
        assertMatch($object1->getESign(), $object2->getESign());

        assertMatch("789", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());

        assertMatch($object1->toFloat(), $object2->toFloat());

        assertMatch($object1->toNumber(), $object2->toNumber());
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
}
