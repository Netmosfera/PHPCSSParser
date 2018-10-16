<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSAST\Tokenizer\eatNumberToken;
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
 * #1  | FALSE if .  followed by non-digit (includes EOF)
 *
 * #2  | FALSE if    non-digit (includes EOF)
 *
 * #3  | INTEGER_PART | DECIMAL_PART || E_PART
 * #4  | INTEGER_PART | DECIMAL_PART || ------ + incomplete E_PART
 *
 * #5  | INTEGER_PART | ------------ || E_PART
 * #6  | INTEGER_PART | ------------ || ------ + incomplete DECIMAL_PART and E_PART
 *
 * #7  | ------------ | DECIMAL_PART || E_PART
 * #8  | ------------ | DECIMAL_PART || ------ + incomplete E_PART
  */
class eatNumberTokenTest extends TestCase
{
    public function optionalSign(){
        return ["+", "-", ""];
    }

    public function restAfterWholePart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->remove(cp("5")); // excluded as this would be part of the integer
        $sequences = getSampleCodePointsFromRanges($set);
        $sequences = array_merge($sequences, ANY_UTF8("not starting with a continuation"));
        $sequences[] = ".a"; // makes sure it doesn't eat incomplete decimals
        $sequences[] = "ea"; // makes sure it doesn't eat incomplete E_PART
        $sequences[] = "Ea"; // makes sure it doesn't eat incomplete E_PART
        $sequences[] = "e+a"; // makes sure it doesn't eat incomplete E_PART
        $sequences[] = "E+a"; // makes sure it doesn't eat incomplete E_PART
        return $sequences;
    }

    public function restAfterDecimalPart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->remove(cp("5")); // excluded as this would be part of the decimal part
        $sequences = getSampleCodePointsFromRanges($set);
        $sequences = array_merge($sequences, ANY_UTF8("not starting with a continuation"));
        $sequences[] = ".555"; // makes sure it doesn't eat decimals twice
        $sequences[] = "ea"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "Ea"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "e+a"; // makes sure it doesn't eat an incomplete E_PART
        $sequences[] = "E+a"; // makes sure it doesn't eat an incomplete E_PART
        return $sequences;
    }

    public function restAfterEPart(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->remove(cp("5")); // excluded as this would be part of the exponent
        $sequences = getSampleCodePointsFromRanges($set);
        $sequences = array_merge($sequences, ANY_UTF8("not starting with a continuation"));
        $sequences[] = ".555"; // makes sure it doesn't eat decimals twice
        $sequences[] = "E555"; // makes sure it doesn't eat e-part twice
        return $sequences;
    }

    //------------------------------------------------------------------------------------

    public function data1(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->remove(cp("5"));
        $sequences = getSampleCodePointsFromRanges($set);
        $sequences = array_merge($sequences, ANY_UTF8("not starting with a digit"));
        $sequences[] = ".555"; // makes sure it doesn't eat decimals after two .
        $sequences[] = "E555"; // makes sure it doesn't eat e-part after no number
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            $sequences
        );
    }

    /** @dataProvider data1 */
    public function test1(String $prefix, String $sign, String $rest){
        $number = NULL;

        $traverser = getTraverser($prefix, $sign . "." . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $sign . "." . $rest);
    }

    public function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->remove(cp("5"));
        $sequences = getSampleCodePointsFromRanges($set);
        $sequences = array_merge($sequences, ANY_UTF8("not starting with a digit"));
        $sequences[] = ".a"; // makes sure it doesn't eat invalid decimals
        $sequences[] = "E555"; // makes sure it doesn't eat e-part after no number
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            $sequences
        );
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $sign, String $rest){
        $number = NULL;

        $traverser = getTraverser($prefix, $sign . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $sign . $rest);
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["e", "E"],
            $this->optionalSign(),
            $this->restAfterEPart()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $sign, String $eIndicator, String $eSign, String $rest){
        $number = new CheckedNumberToken($sign, "555", "555", $eIndicator, $eSign, "555");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data4(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            $this->restAfterDecimalPart()
        );
    }

    /** @dataProvider data4 */
    public function test4(String $prefix, String $sign, String $rest){
        $number = new CheckedNumberToken($sign, "555", "555", "", "", "");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data5(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["e", "E"],
            $this->optionalSign(),
            $this->restAfterEPart()
        );
    }

    /** @dataProvider data5 */
    public function test5(String $prefix, String $sign, String $eIndicator, String $eSign, String $rest){
        $number = new CheckedNumberToken($sign, "555", "", $eIndicator, $eSign, "555");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data6(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            $this->restAfterWholePart()
        );
    }

    /** @dataProvider data6 */
    public function test6(String $prefix, String $sign, String $rest){
        $number = new CheckedNumberToken($sign, "555", "", "", "", "");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data7(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            ["e", "E"],
            $this->optionalSign(),
            $this->restAfterEPart()
        );
    }

    /** @dataProvider data7 */
    public function test7(String $prefix, String $sign, String $eIndicator, String $eSign, String $rest){
        $number = new CheckedNumberToken($sign, "", "555", $eIndicator, $eSign, "555");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data8(){
        return cartesianProduct(
            ANY_UTF8(),
            $this->optionalSign(),
            $this->restAfterDecimalPart()
        );
    }

    /** @dataProvider data8 */
    public function test8(String $prefix, String $sign, String $rest){
        $number = new CheckedNumberToken($sign, "", "555", "", "", "");

        $traverser = getTraverser($prefix, $number . $rest);
        $actualNumber = eatNumberToken($traverser, "5");

        assertMatch($actualNumber, $number);
        assertMatch($traverser->eatAll(), $rest);
    }
}
