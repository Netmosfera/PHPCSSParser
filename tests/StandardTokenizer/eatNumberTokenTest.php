<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatNumberToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * INTEGER_PART = at least one digit
 * DECIMAL_PART = . and at least one digit
 * E_PART       = e or E, optionally followed by a sign, followed by at least one digit
 *
 * #1  | FALSE if .  followed by non-digit
 * #2  | FALSE if .  followed by EOF
 *
 * #3  | FALSE if    non-digit
 * #4  | FALSE if    EOF
 *
 * #5  | INTEGER_PART | DECIMAL_PART || E_PART
 * #6  | INTEGER_PART | DECIMAL_PART || ------ + incomplete E_PART
 *
 * #7  | INTEGER_PART | ------------ || E_PART
 * #8  | INTEGER_PART | ------------ || ------ + incomplete DECIMAL_PART and E_PART
 *
 * #9  | ------------ | DECIMAL_PART || E_PART
 * #10 | ------------ | DECIMAL_PART || ------ + incomplete E_PART
  */
class eatNumberTokenTest extends TestCase
{
    public function data1(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("5"));
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            getCodePointsFromRanges($codePoints),
            ANY_UTF8()
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $sign, String $nonDigit, String $rest){
        $traverser = getTraverser($prefix, $sign . "." . $nonDigit . $rest);
        $expected = NULL;
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $sign . "." . $nonDigit . $rest);
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), $this->optionalSign());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $sign){
        $traverser = getTraverser($prefix, $sign . ".");
        $expected = NULL;
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $sign . ".");
    }

    public function data3(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("5"));
        $codePoints->remove(cp("."));
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            getCodePointsFromRanges($codePoints),
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $sign, String $nonDigit, String $rest){
        $traverser = getTraverser($prefix, $sign . $nonDigit . $rest);
        $expected = NULL;
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $sign . $nonDigit . $rest);
    }

    public function data4(){
        return cartesianProduct(ANY_UTF8(), $this->optionalSign());
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $sign){
        $traverser = getTraverser($prefix, $sign);
        $expected = NULL;
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $sign);
    }

    public function data5(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            ["e", "E"],
            $this->optionalSign(),
            $this->notNumberContinuationAfterEPart()
        );
    }

    /** @dataProvider data5 */
    public function test5(
        String $prefix,
        String $sign,
        String $digits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $traverser = getTraverser(
            $prefix,
            $sign . $digits . "." . $digits . $eLetter . $eSign . $digits . $rest
        );
        $expected = new CheckedNumberToken(
            $sign,
            $digits,
            $digits,
            $eLetter,
            $eSign,
            $digits
        );
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data6(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            $this->notNumberContinuationAfterDecimalPart()
        );
    }

    /** @dataProvider data6 */
    public function test6(String $prefix, String $sign, String $digits, String $rest){
        $traverser = getTraverser($prefix, $sign . $digits . "." . $digits . $rest);
        $expected = new CheckedNumberToken($sign, $digits, $digits, "", "", "");
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data7(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            ["e", "E"],
            $this->optionalSign(),
            $this->notNumberContinuationAfterEPart()
        );
    }

    /** @dataProvider data7 */
    public function test7(
        String $prefix,
        String $sign,
        String $digits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $traverser = getTraverser(
            $prefix,
            $sign . $digits . $eLetter . $eSign . $digits . $rest
        );
        $expected = new CheckedNumberToken($sign, $digits, "", $eLetter, $eSign, $digits);
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data8(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            $this->notNumberContinuationAfterIntegerPart()
        );
    }

    /** @dataProvider data8 */
    public function test8(
        String $prefix,
        String $sign,
        String $digits,
        String $rest
    ){
        $traverser = getTraverser($prefix, $sign . $digits . $rest);
        $expected = new CheckedNumberToken($sign, $digits, "", "", "", "");
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data9(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            ["e", "E"],
            $this->optionalSign(),
            $this->notNumberContinuationAfterEPart()
        );
    }

    /** @dataProvider data9 */
    public function test9(
        String $prefix,
        String $sign,
        String $digits,
        String $eLetter,
        String $eSign,
        String $rest
    ){
        $traverser = getTraverser(
            $prefix,
            $sign . "." . $digits . $eLetter . $eSign . $digits . $rest
        );
        $expected = new CheckedNumberToken($sign, "", $digits, $eLetter, $eSign, $digits);
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data10(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["5", "55", "555", "5555"],
            $this->notNumberContinuationAfterDecimalPart()
        );
    }

    /** @dataProvider data10 */
    public function test10(String $prefix, String $sign, String $digits, String $rest){
        $traverser = getTraverser($prefix, $sign . "." . $digits . $rest);
        $expected = new CheckedNumberToken($sign, "", $digits, "", "", "");
        $actual = eatNumberToken($traverser, "5");
        assertMatch($actual, $expected);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function optionalSign(){
        return ["+", "-", ""];
    }

    /**
     * -
     *
     * Any CodePoint sequence:
     *
     * - not starting with a digit
     * - not starting with "e|E" and a digit
     * - not starting with "e|E" and "+|-" and a digit
     */
    public function notNumberContinuationAfterDecimalPart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $sequences = getCodePointsFromRanges($set);
        $sequences[] = "sample \u{2764} string";
        $sequences[] = " \t \n \r \r\n \f "; // makes sure it doesn't touch whitespace
        $sequences[] = ".42"; // makes sure it doesn't eat decimals twice (?)
        $sequences[] = "ea"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "Ea"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "e+a"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "E+a"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = ""; // makes sure it deals with EOF correctly
        return $sequences;
    }

    /**
     * -
     *
     * Any CodePoint sequence:
     *
     * - not starting with a digit
     */
    public function notNumberContinuationAfterEPart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $sequences = getCodePointsFromRanges($set);
        $sequences[] = "sample \u{2764} string";
        $sequences[] = " \t \n \r \r\n \f "; // makes sure it doesn't touch whitespace
        $sequences[] = ""; // makes sure it deals with EOF correctly
        return $sequences;
    }

    /**
     * -
     *
     * Any CodePoint sequence:
     *
     * - not starting with a digit
     * - not starting with "." and a digit
     * - not starting with "e|E" and a digit
     * - not starting with "e|E" and "+|-" and a digit
     */
    public function notNumberContinuationAfterIntegerPart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $sequences = getCodePointsFromRanges($set);
        $sequences[] = " \t \n \r \r\n \f ";
        $sequences[] = "sample \u{2764} string";
        $sequences[] = ".a";
        $sequences[] = "ea";
        $sequences[] = "Ea";
        $sequences[] = "e+a";
        $sequences[] = "E+a";
        $sequences[] = "";
        return $sequences;
    }
}
