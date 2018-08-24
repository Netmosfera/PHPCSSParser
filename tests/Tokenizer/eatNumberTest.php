<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_STRING;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNumber;
use function Netmosfera\PHPCSSASTDev\Examples\ONE_OR_MORE_DIGITS;
use function Netmosfera\PHPCSSASTDev\Examples\OPTIONAL_NUMBER_SIGN;
use function Netmosfera\PHPCSSASTDev\Examples\NOT_A_NUMBER_CONTINUATION_AFTER_E_PART;
use function Netmosfera\PHPCSSASTDev\Examples\NOT_A_NUMBER_CONTINUATION_AFTER_INTEGER_PART;
use function Netmosfera\PHPCSSASTDev\Examples\NOT_A_NUMBER_CONTINUATION_AFTER_DECIMAL_PART;
use Netmosfera\PHPCSSAST\Tokens\_Number;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * INTEGER_PART = at least one digit
 * DECIMAL_PART = . and at least one digit
 * E_PART       = e or E, optionally followed by a sign, followed by at least one digit
 *
 * #1 | INTEGER_PART | DECIMAL_PART || E_PART
 * #2 | INTEGER_PART | DECIMAL_PART || xxxxxx + incomplete E_PART
 *
 * #3 | INTEGER_PART | xxxxxxxxxxxx || E_PART
 * #4 | INTEGER_PART | xxxxxxxxxxxx || xxxxxx + incomplete DECIMAL_PART and E_PART
 *
 * #5 | xxxxxxxxxxxx | DECIMAL_PART || E_PART
 * #6 | xxxxxxxxxxxx | DECIMAL_PART || xxxxxx + incomplete E_PART
  */
class eatNumberTest extends TestCase
{
    function data_1_INTEGER_PART_followed_by_DECIMAL_PART_and_E_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            ["e", "E"],
            OPTIONAL_NUMBER_SIGN(),
            NOT_A_NUMBER_CONTINUATION_AFTER_E_PART()
        );
    }

    /** @dataProvider data_1_INTEGER_PART_followed_by_DECIMAL_PART_and_E_PART */
    function test_1_INTEGER_PART_followed_by_DECIMAL_PART_and_E_PART($prefix, $sign, $digits, $eLetter, $eSign, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, $digits, $digits, $eLetter, $eSign === "" ? NULL : $eSign, $digits);
        $t = new Traverser($prefix . $sign . $digits . "." . $digits . $eLetter . $eSign . $digits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_2_INTEGER_PART_followed_by_DECIMAL_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            NOT_A_NUMBER_CONTINUATION_AFTER_DECIMAL_PART()
        );
    }

    /** @dataProvider data_2_INTEGER_PART_followed_by_DECIMAL_PART */
    function test_2_INTEGER_PART_followed_by_DECIMAL_PART($prefix, $sign, $digits, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, $digits, $digits, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . $digits . "." . $digits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_3_INTEGER_PART_followed_by_E_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            ["e", "E"],
            OPTIONAL_NUMBER_SIGN(),
            NOT_A_NUMBER_CONTINUATION_AFTER_E_PART()
        );
    }

    /** @dataProvider data_3_INTEGER_PART_followed_by_E_PART */
    function test_3_INTEGER_PART_followed_by_E_PART($prefix, $sign, $digits, $eLetter, $eSign, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, $digits, NULL, $eLetter, $eSign === "" ? NULL : $eSign, $digits);
        $t = new Traverser($prefix . $sign . $digits . $eLetter . $eSign . $digits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_4_INTEGER_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            NOT_A_NUMBER_CONTINUATION_AFTER_INTEGER_PART()
        );
    }

    /** @dataProvider data_4_INTEGER_PART */
    function test_4_INTEGER_PART($prefix, $sign, $intDigits, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, $intDigits, NULL, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . $intDigits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_5_DECIMAL_PART_and_E_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            ["e", "E"],
            OPTIONAL_NUMBER_SIGN(),
            NOT_A_NUMBER_CONTINUATION_AFTER_E_PART()
        );
    }

    /** @dataProvider data_5_DECIMAL_PART_and_E_PART */
    function test_5_DECIMAL_PART_and_E_PART($prefix, $sign, $digits, $eLetter, $eSign, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, NULL, $digits, $eLetter, $eSign === "" ? NULL : $eSign, $digits);
        $t = new Traverser($prefix . $sign . "." . $digits . $eLetter . $eSign . $digits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_6_DECIMAL_PART(){
        return cartesianProduct(
            ANY_STRING(),
            OPTIONAL_NUMBER_SIGN(),
            ONE_OR_MORE_DIGITS(),
            NOT_A_NUMBER_CONTINUATION_AFTER_DECIMAL_PART()
        );
    }

    /** @dataProvider data_6_DECIMAL_PART */
    function test_6_DECIMAL_PART($prefix, $sign, $decimalDigits, $rest){
        $expected = new _Number($sign === "" ? NULL : $sign, NULL, $decimalDigits, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . "." . $decimalDigits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
