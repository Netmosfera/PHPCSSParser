<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSASTDev\Examples\getAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTDev\Examples\getEitherEmptyOrNonEmptyAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatNumber;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTDev\Examples\getDigitSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getDigitsSet;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\Tokens\_Number;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * DECIMAL_PART = . and at least one digit
 * E_PART = e or E, optionally followed by a sign, followed by at least one digit
 *
 * #77 | integer digits followed by DECIMAL_PART and E_PART
 *
 *
 * #1  | only integer digits followed anything except DECIMAL_PART or E_PART
 * #2  | only integer digits followed by an incomplete DECIMAL_PART
 * #3  | only integer digits followed by an incomplete E_PART (E and optionally a sign without a digit)
 *
 * #4  | only decimal digits followed by anything except E_PART
 * #5  | only decimal digits followed by an incomplete E_PART
  */
class eatNumberTest extends TestCase
{
    function getOptionalSigns(){
        return ["+", "-", ""];
    }

    function getSequencesNotStartingWithADigit(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->removeAll(getDigitsSet());
        $sequences = getCodePointsFromRanges($codePoints);
        $sequences[] = "";
        $sequences[] = "\u{2764}";
        $sequences[] = "skip \u{2764} me";
        return $sequences;
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // # 77

    function data_integer_digits_followed_by_DECIMAL_PART_and_E_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            ["e", "E"],
            $this->getOptionalSigns(),
            getAnyCodePointSeqsSet()
        );
    }

    /** @dataProvider data_integer_digits_followed_by_DECIMAL_PART_and_E_PART */
    function test_integer_digits_followed_by_DECIMAL_PART_and_E_PART(
        String $prefix,
        String $sign,
        String $digits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, $digits, $digits, $eLetter, $eSign === "" ? NULL : $eSign, $digits);
        $t = new Traverser($prefix . $sign . $digits . "." . $digits . $eLetter . $eSign . $digits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }


    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // # 1

    function data_integer_followed_by_anything_except_DECIMAL_PART_or_E_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            $this->getSequencesNotStartingWithADigit()
        );
    }

    /** @dataProvider data_integer_followed_by_anything_except_DECIMAL_PART_or_E_PART */
    function test_integer_followed_by_anything_except_DECIMAL_PART_or_E_PART(
        String $prefix,
        String $sign,
        String $intDigits,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, $intDigits, NULL, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . $intDigits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // # 2

    function data_integer_followed_by_an_incomplete_DECIMAL_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            $this->getSequencesNotStartingWithADigit()
        );
    }

    /** @dataProvider data_integer_followed_by_an_incomplete_DECIMAL_PART */
    function test_integer_followed_by_an_incomplete_DECIMAL_PART(
        String $prefix,
        String $sign,
        String $intDigits,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, $intDigits, NULL, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . $intDigits . "." . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), "." . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // # 3

    function data_integer_followed_by_an_incomplete_E_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            ["e", "E"],
            $this->getOptionalSigns(),
            $this->getSequencesNotStartingWithADigit()
        );
    }

    /** @dataProvider data_integer_followed_by_an_incomplete_E_PART */
    function test_integer_followed_by_an_incomplete_E_PART(
        String $prefix,
        String $sign,
        String $intDigits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, $intDigits, NULL, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . $intDigits . $eLetter . $eSign . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $eLetter . $eSign . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // # 4

    function data_decimals_followed_by_anything_except_E_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            $this->getSequencesNotStartingWithADigit()
        );
    }

    /** @dataProvider data_decimals_followed_by_anything_except_E_PART */
    function test_decimals_followed_by_anything_except_E_PART(
        String $prefix,
        String $sign,
        String $decimalDigits,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, NULL, $decimalDigits, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . "." . $decimalDigits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #5

    function data_decimals_followed_by_an_incomplete_E_PART(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            $this->getOptionalSigns(),
            getDigitSeqsSet(),
            ["e", "E"],
            $this->getOptionalSigns(),
            $this->getSequencesNotStartingWithADigit()
        );
    }

    /** @dataProvider data_decimals_followed_by_an_incomplete_E_PART */
    function test_decimals_followed_by_an_incomplete_E_PART(
        String $prefix,
        String $sign,
        String $decimalDigits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $expected = new _Number($sign === "" ? NULL : $sign, NULL, $decimalDigits, NULL, NULL, NULL);
        $t = new Traverser($prefix . $sign . "." . $decimalDigits . $eLetter . $eSign . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumber($t), $expected));
        self::assertTrue(match($t->eatAll(), $eLetter . $eSign . $rest));
    }
}
