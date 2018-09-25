<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters only wholes
 * #2 | test getters only decimals
 * #3 | test getters wholes and decimals
 * #4 | test getters only wholes and e-part
 * #5 | test getters only decimals and e-part
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
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data4(){
        return cartesianProduct(["-", "+", ""], ["e", "E"], ["-", "+", ""]);
    }

    /** @dataProvider data4 */
    function test4(String $sign, String $e, String $eSign){
        $object1 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "789");
        $object2 = new CheckedNumberToken($sign, "123", "", $e, $eSign, "789");

        assertMatch($object1, $object2);

        assertMatch($sign . "123" . $e . $eSign . "789", (String)$object1);
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

        assertMatch("789", $object1->getEExponent());
        assertMatch($object1->getEExponent(), $object2->getEExponent());
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
    }
}
