<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\Escapes\isValidEscape;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class isValidEscapeTest extends TestCase
{
    function data_returns_FALSE_if_newline(){
        return cartesianProduct(
            ANY_UTF8(),
            getNewlineSeqsSet(),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_FALSE_if_newline */
    function test_returns_FALSE_if_newline(String $prefix, String $invalidEscape, String $rest){
        $t = new Traverser($prefix . $invalidEscape . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(isValidEscape($t), FALSE);
        assertMatch($t->eatAll(), $invalidEscape . $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_FALSE_if_EOF(){
        return cartesianProduct(
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_FALSE_if_EOF */
    function test_returns_FALSE_if_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        assertMatch(isValidEscape($t), FALSE);
        assertMatch($t->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_returns_TRUE_if_not_newline_or_EOF(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getNewlinesSet());
        return cartesianProduct(
            ANY_UTF8(),
            getCodePointsFromRanges($codePoints),
            ANY_UTF8()
        );
    }

    /** @dataProvider data_returns_TRUE_if_not_newline_or_EOF */
    function test_returns_TRUE_if_not_newline_or_EOF(String $prefix, String $notNewlineCodePoint, String $rest){
        $t = new Traverser($prefix . $notNewlineCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(isValidEscape($t), TRUE);
        assertMatch($t->eatAll(), $notNewlineCodePoint . $rest);
    }
}
