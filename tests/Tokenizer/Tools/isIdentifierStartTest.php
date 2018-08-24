<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Examples\getAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTDev\Examples\getEitherEmptyOrNonEmptyAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNameStartersSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getValidEscapesSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\isIdentifierStart;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\cp;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * 3 code points
 * #1  | TRUE  if -\ is followed a valid escape code point
 * #2  | FALSE if -\ is followed an invalid escape code point
 * #3  | FALSE if -\ is followed by EOF
 *
 * 2 code points
 * #4  | TRUE  if - is followed by -
 * #5  | TRUE  if - is followed by a name-start code point
 * #6  | FALSE if - is followed by none of -, \ or a name-start code point
 * #7  | FALSE if - is followed by EOF
 *
 * 2 code points
 * #8  | TRUE  if \ is followed by valid escape code point
 * #9  | FALSE if \ is followed by invalid escape code point
 * #10 | FALSE if \ is followed by EOF
 *
 * 1 code point
 * #11  | TRUE  if the next code point is a name-start code point
 * #12  | FALSE if the next code point is none of a name-start code point, \ or -
 * #13  | FALSE if the next code point is EOF
 */
class isIdentifierStartTest extends TestCase
{
    // #1

    function data_returns_TRUE_if_minus_and_backslash_are_followed_by_a_valid_escape_code_point(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges(getValidEscapesSet()), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_TRUE_if_minus_and_backslash_are_followed_by_a_valid_escape_code_point */
    function test_returns_TRUE_if_minus_and_backslash_are_followed_by_a_valid_escape_code_point(String $prefix, String $escapeCodePoint, String $rest){
        $t = new Traverser($prefix . "-\\" . $escapeCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), "-\\" . $escapeCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #2

    function data_returns_FALSE_if_minus_and_backslash_are_followed_by_an_invalid_escape_code_point(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getValidEscapesSet());
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges($codePoints), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_minus_and_backslash_are_followed_by_an_invalid_escape_code_point */
    function test_returns_FALSE_if_minus_and_backslash_are_followed_by_an_invalid_escape_code_point(String $prefix, String $nonEscapeCodePoint, String $rest){
        $t = new Traverser($prefix . "-\\" . $nonEscapeCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "-\\" . $nonEscapeCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #3

    function data_returns_FALSE_if_minus_and_backslash_are_followed_by_EOF(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getValidEscapesSet());
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges($codePoints), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_minus_and_backslash_are_followed_by_EOF */
    function test_returns_FALSE_if_minus_and_backslash_are_followed_by_EOF(String $prefix){
        $t = new Traverser($prefix . "-\\", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "-\\"));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #4

    function data_returns_TRUE_if_minus_is_followed_by_minus(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_TRUE_if_minus_is_followed_by_minus */
    function test_returns_TRUE_if_minus_is_followed_by_minus(String $prefix, String $rest){
        $t = new Traverser($prefix . "--" . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), "--" . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #5

    function data_returns_TRUE_if_minus_is_followed_by_a_name_start_code_point(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges(getNameStartersSet()), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_TRUE_if_minus_is_followed_by_a_name_start_code_point */
    function test_returns_TRUE_if_minus_is_followed_by_a_name_start_code_point(String $prefix, String $nameStartCodePoint, String $rest){
        $t = new Traverser($prefix . "-" . $nameStartCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), "-" . $nameStartCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #6

    function data_returns_FALSE_if_minus_is_followed_by_none_of_a_minus_or_a_name_start_code_point(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("-"));
        $codePoints->remove(cp("\\"));
        $codePoints->removeAll(getNameStartersSet());
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges($codePoints), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_minus_is_followed_by_none_of_a_minus_or_a_name_start_code_point */
    function test_returns_FALSE_if_minus_is_followed_by_none_of_a_minus_or_a_name_start_code_point(String $prefix, String $nonMinusNonNameStartCodePoint, String $rest){
        $t = new Traverser($prefix . "-" . $nonMinusNonNameStartCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "-" . $nonMinusNonNameStartCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #7

    function data_returns_FALSE_if_minus_is_followed_by_EOF(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_minus_is_followed_by_EOF */
    function test_returns_FALSE_if_minus_is_followed_by_EOF(String $prefix){
        $t = new Traverser($prefix . "-", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "-"));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #8

    function data_returns_TRUE_if_backslash_is_followed_by_a_valid_escape_code_point(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges(getValidEscapesSet()), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_TRUE_if_backslash_is_followed_by_a_valid_escape_code_point */
    function test_returns_TRUE_if_backslash_is_followed_by_a_valid_escape_code_point(String $prefix, String $validEscapeCodePoint, String $rest){
        $t = new Traverser($prefix . "\\" . $validEscapeCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), "\\" . $validEscapeCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #9

    function data_returns_FALSE_if_backslash_is_followed_by_an_invalid_escape_code_point(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getValidEscapesSet());
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges($codePoints), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_backslash_is_followed_by_an_invalid_escape_code_point */
    function test_returns_FALSE_if_backslash_is_followed_by_an_invalid_escape_code_point(String $prefix, String $invalidEscapeCodePoint, String $rest){
        $t = new Traverser($prefix . "\\" . $invalidEscapeCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "\\" . $invalidEscapeCodePoint . $rest));
    }
    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #10

    function data_returns_FALSE_if_backslash_is_followed_by_EOF(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_backslash_is_followed_by_EOF */
    function test_returns_FALSE_if_backslash_is_followed_by_EOF(String $prefix){
        $t = new Traverser($prefix . "\\", TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), "\\"));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #11

    function data_returns_TRUE_if_the_next_code_point_is_a_name_start_code_point(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges(getNameStartersSet()), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_TRUE_if_the_next_code_point_is_a_name_start_code_point */
    function test_returns_TRUE_if_the_next_code_point_is_a_name_start_code_point(String $prefix, String $nameStartCodePoint, String $rest){
        $t = new Traverser($prefix . $nameStartCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), TRUE));
        self::assertTrue(match($t->eatAll(), $nameStartCodePoint . $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #12

    function data_returns_FALSE_if_the_next_code_point_is_none_of_a_name_start_code_point_a_backslash_or_a_minus(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->remove(cp("-"));
        $codePoints->remove(cp("\\"));
        $codePoints->removeAll(getNameStartersSet());
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getCodePointsFromRanges($codePoints), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_none_of_a_name_start_code_point_a_backslash_or_a_minus */
    function test_returns_FALSE_if_the_next_code_point_is_none_of_a_name_start_code_point_a_backslash_or_a_minus(String $prefix, String $invalidCodePoint, String $rest){
        $t = new Traverser($prefix . $invalidCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), $invalidCodePoint . $rest));
    }
    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    // #13

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(isIdentifierStart($t), FALSE));
        self::assertTrue(match($t->eatAll(), ""));
    }
}
