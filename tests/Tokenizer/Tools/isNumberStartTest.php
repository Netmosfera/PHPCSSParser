<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTTests\Tokenizer\getSeqOfAnyCodePoint;
use function Netmosfera\PHPCSSASTTests\Tokenizer\getPrefixes;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isNumberStart;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTDev\Sets\getDigitCodePointSet;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\cp;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1  | TRUE  if +. followed by digit     | Y
 * #2  | FALSE if +. followed by non-digit | Y
 * #3  | FALSE if +. followed by EOF       | Y
 *
 * #4  | TRUE  if +  followed by digit     | Y
 * #5  | FALSE if +  followed by non-digit | Y
 * #6  | FALSE if +  followed by EOF       | Y
 *
 * #7  | TRUE  if .  followed by digit     |
 * #8  | FALSE if .  followed by non-digit |
 * #9  | FALSE if .  followed by EOF       |
 *
 * #10 | TRUE  if    digit                 |
 * #11 | FALSE if    non-digit             |
 * #12 | FALSE if    EOF                   |
 */
class isNumberStartTest extends TestCase
{
    function getSigns(){
        return ["+", "-"];
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #1

    function data_returns_TRUE_if_sign_and_full_stop_are_followed_by_a_digit(){
        return cartesianProduct(getPrefixes(), $this->getSigns(), getCodePointsFromRanges(getDigitCodePointSet()), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_TRUE_if_sign_and_full_stop_are_followed_by_a_digit */
    function test_returns_TRUE_if_sign_and_full_stop_are_followed_by_a_digit(String $prefix, String $sign, String $digit, String $rest){
        $t = new Traverser($prefix . $sign . "." . $digit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), $sign . "." . $digit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #2

    function data_returns_FALSE_if_sign_and_full_stop_are_followed_by_a_non_digit(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getDigitCodePointSet());
        return cartesianProduct(getPrefixes(), $this->getSigns(), getCodePointsFromRanges($codePoints), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_FALSE_if_sign_and_full_stop_are_followed_by_a_non_digit */
    function test_returns_FALSE_if_sign_and_full_stop_are_followed_by_a_non_digit(String $prefix, String $sign, String $nonDigit, String $rest){
        $t = new Traverser($prefix . $sign . "." . $nonDigit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), $sign . "." . $nonDigit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #3

    function data_returns_FALSE_if_sign_and_full_stop_are_followed_by_EOF(){
        return cartesianProduct(getPrefixes(), $this->getSigns());
    }

    /** @dataProvider data_returns_FALSE_if_sign_and_full_stop_are_followed_by_EOF */
    function test_returns_FALSE_if_sign_and_full_stop_are_followed_by_EOF(String $prefix, String $sign){
        $t = new Traverser($prefix . $sign . ".", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), $sign . "."));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #4

    // Test that returns TRUE if sign is followed by a digit
    function data_returns_TRUE_if_sign_is_followed_by_a_digit(){
        return cartesianProduct(getPrefixes(), $this->getSigns(), getCodePointsFromRanges(getDigitCodePointSet()), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_TRUE_if_sign_is_followed_by_a_digit */
    function test_returns_TRUE_if_sign_is_followed_by_a_digit(String $prefix, String $sign, String $digit, String $rest){
        $t = new Traverser($prefix . $sign . $digit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), $sign . $digit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #5

    function data_returns_FALSE_if_sign_is_followed_by_a_non_digit(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getDigitCodePointSet());
        $codePoints->remove(cp("."));
        return cartesianProduct(getPrefixes(), $this->getSigns(), getCodePointsFromRanges($codePoints), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_FALSE_if_sign_is_followed_by_a_non_digit */
    function test_returns_FALSE_if_sign_is_followed_by_a_non_digit(String $prefix, String $sign, String $nonDigit, String $rest){
        $t = new Traverser($prefix . $sign . $nonDigit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), $sign . $nonDigit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #6

    function data_returns_FALSE_if_sign_is_followed_by_EOF(){
        return cartesianProduct(getPrefixes(), $this->getSigns());
    }

    /** @dataProvider data_returns_FALSE_if_sign_is_followed_by_EOF */
    function test_returns_FALSE_if_sign_is_followed_by_EOF(String $prefix, String $sign){
        $t = new Traverser($prefix . $sign, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), $sign));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #7

    function data_returns_TRUE_if_full_stop_is_followed_by_a_digit(){
        return cartesianProduct(getPrefixes(), getCodePointsFromRanges(getDigitCodePointSet()), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_TRUE_if_full_stop_is_followed_by_a_digit */
    function test_returns_TRUE_if_full_stop_is_followed_by_a_digit(String $prefix, String $digit, String $rest){
        $t = new Traverser($prefix . "." . $digit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), "." . $digit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #8

    function data_returns_FALSE_if_full_stop_is_followed_by_a_non_digit(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getDigitCodePointSet());
        $codePoints->remove(cp("."));
        return cartesianProduct(getPrefixes(), getCodePointsFromRanges($codePoints), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_FALSE_if_full_stop_is_followed_by_a_non_digit */
    function test_returns_FALSE_if_full_stop_is_followed_by_a_non_digit(String $prefix, String $nonDigit, String $rest){
        $t = new Traverser($prefix . "." . $nonDigit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "." . $nonDigit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #9

    function data_returns_FALSE_if_full_stop_is_followed_by_EOF(){
        return cartesianProduct(getPrefixes());
    }

    /** @dataProvider data_returns_FALSE_if_full_stop_is_followed_by_EOF */
    function test_returns_FALSE_if_full_stop_is_followed_by_EOF(String $prefix){
        $t = new Traverser($prefix . ".", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "."));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #10

    function data_returns_TRUE_if_the_next_code_point_is_a_digit(){
        return cartesianProduct(getPrefixes(), getCodePointsFromRanges(getDigitCodePointSet()), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_TRUE_if_the_next_code_point_is_a_digit */
    function test_returns_TRUE_if_the_next_code_point_is_a_digit(String $prefix, String $digit, String $rest){
        $t = new Traverser($prefix . $digit . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isNumberStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), $digit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #11

    function data_returns_FALSE_if_the_next_code_point_is_a_non_digit(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("."));
        $codePoints->removeAll($this->getSigns());
        $codePoints->removeAll(getDigitCodePointSet());
        return cartesianProduct(getPrefixes(), getCodePointsFromRanges($codePoints), getSeqOfAnyCodePoint());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_a_non_digit */
    function test_returns_FALSE_if_the_next_code_point_is_a_non_digit(String $prefix, String $nonDigit, String $rest){
        $t = new Traverser($prefix . $nonDigit . $rest, TRUE);
        $t->eatStr($prefix);
        $actual = isNumberStart($t);
        self::assertTrue(match($actual, FALSE));
        self::assertTrue(match($t->eatAll(), $nonDigit . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #12

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(getPrefixes());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        $actual = isNumberStart($t);
        self::assertTrue(match($actual, FALSE));
        self::assertTrue(match($t->eatAll(), ""));
    }
}
