<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

abstract class eatSingleCodePointTest extends TestCase
{
    abstract function getExpectedCodePointSet(): CompressedCodePointSet;

    abstract function getEatFunction(): Closure; // Closure(Traverser): String

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_TRUE_if_the_next_code_point_is_the_expected_code_point(){
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges($this->getExpectedCodePointSet()),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_TRUE_if_the_next_code_point_is_the_expected_code_point */
    function test_returns_TRUE_if_the_next_code_point_is_the_expected_code_point(String $prefix, String $expectedCodePoint, String $rest ){
        $t = new Traverser($prefix . $expectedCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch($this->getEatFunction()($t), $expectedCodePoint);
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_not_the_expected_code_point(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll($this->getExpectedCodePointSet());
        return cartesianProduct(ANY_UTF8(), getCodePointsFromRanges($codePoints), ANY_UTF8());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_not_the_expected_code_point */
    function test_returns_FALSE_if_the_next_code_point_is_not_the_expected_code_point(String $prefix, String $unexpectedCodePoint, String $rest){
        $t = new Traverser($prefix . $unexpectedCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch($this->getEatFunction()($t), NULL);
        assertMatch($t->eatAll(), $unexpectedCodePoint . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_the_next_code_point_is_EOF(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data_returns_FALSE_if_the_next_code_point_is_EOF */
    function test_returns_FALSE_if_the_next_code_point_is_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        assertMatch($this->getEatFunction()($t), NULL);
        assertMatch($t->eatAll(), "");
    }
}
