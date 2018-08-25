<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\EOFEscape;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\PlainEscape;
use Netmosfera\PHPCSSAST\Tokens\SubTokens\ActualEscape;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Examples\ONE_TO_SIX_HEX_DIGITS;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatEscape;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatEscapeTest extends TestCase
{
    function data_actual(){
        return cartesianProduct(
            ANY_UTF8(),
            ONE_TO_SIX_HEX_DIGITS(),
            ["", "skip \u{2764} me"]
        );
    }

    /** @dataProvider data_actual */
    function test_actual(String $prefix, String $hexDigits, String $rest){
        $t = new Traverser($prefix . $hexDigits . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatEscape($t), new ActualEscape($hexDigits, NULL));
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_actual_with_whitespace(){
        return cartesianProduct(ANY_UTF8(), ONE_TO_SIX_HEX_DIGITS(), getWhitespaceSeqsSet(), ANY_UTF8());
    }

    /** @dataProvider data_actual_with_whitespace */
    function test_actual_with_whitespace(String $prefix, String $hexDigits, String $whitespace, String $rest){
        $t = new Traverser($prefix . $hexDigits . $whitespace . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatEscape($t), new ActualEscape($hexDigits, $whitespace));
        assertMatch($t->eatAll(), $rest);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_EOF(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data_EOF */
    function test_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatEscape($t), new EOFEscape());
        assertMatch($t->eatAll(), "");
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_plain(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getHexDigitsSet());
        $seqSet = getCodePointsFromRanges($codePoints);
        $seqSet[] = "\r\n";
        return cartesianProduct(ANY_UTF8(), $seqSet, ANY_UTF8());
    }

    /** @dataProvider data_plain */
    function test_plain(String $prefix, String $plainCodePoint, String $rest){
        $t = new Traverser($prefix . $plainCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        assertMatch(eatEscape($t), new PlainEscape($plainCodePoint));
        assertMatch($t->eatAll(), $rest);
    }
}
