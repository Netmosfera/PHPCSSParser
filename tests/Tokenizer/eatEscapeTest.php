<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Strings\EOFEscape;
use Netmosfera\PHPCSSAST\Tokens\Strings\PlainEscape;
use Netmosfera\PHPCSSASTDev\CompressedCodePointSet;
use function Netmosfera\PHPCSSASTDev\Examples\getEitherEmptyOrNonEmptyAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTDev\Examples\getOneUpToSixHexDigitsSeqsSet;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSets\getHexDigitsSet;
use function Netmosfera\PHPCSSASTDev\Examples\getAnyCodePointSeqsSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Traverser;
use Netmosfera\PHPCSSAST\Tokens\Strings\ActualEscape;
use function Netmosfera\PHPCSSASTDev\SpecData\CodePointSeqsSets\getWhitespaceSeqsSet;
use function Netmosfera\PHPCSSAST\Tokenizer\Tools\eatEscape;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\match;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatEscapeTest extends TestCase
{
    function data_actual(){
        return cartesianProduct(
            getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(),
            getOneUpToSixHexDigitsSeqsSet(),
            ["", "skip \u{2764} me"]
        );
    }

    /** @dataProvider data_actual */
    function test_actual(String $prefix, String $hexDigits, String $rest){
        $t = new Traverser($prefix . $hexDigits . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatEscape($t), new ActualEscape($hexDigits, NULL)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_actual_with_whitespace(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), getOneUpToSixHexDigitsSeqsSet(), getWhitespaceSeqsSet(), getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_actual_with_whitespace */
    function test_actual_with_whitespace(String $prefix, String $hexDigits, String $whitespace, String $rest){
        $t = new Traverser($prefix . $hexDigits . $whitespace . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatEscape($t), new ActualEscape($hexDigits, $whitespace)));
        self::assertTrue(match($t->eatAll(), $rest));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_EOF(){
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet());
    }

    /** @dataProvider data_EOF */
    function test_EOF(String $prefix){
        $t = new Traverser($prefix, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatEscape($t), new EOFEscape()));
        self::assertTrue(match($t->eatAll(), ""));
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data_plain(){
        $codePoints = new CompressedCodePointSet();
        $codePoints->selectAll();
        $codePoints->removeAll(getHexDigitsSet());
        $seqSet = getCodePointsFromRanges($codePoints);
        $seqSet[] = "\r\n";
        return cartesianProduct(getEitherEmptyOrNonEmptyAnyCodePointSeqsSet(), $seqSet, getAnyCodePointSeqsSet());
    }

    /** @dataProvider data_plain */
    function test_plain(String $prefix, String $plainCodePoint, String $rest){
        $t = new Traverser($prefix . $plainCodePoint . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatEscape($t), new PlainEscape($plainCodePoint)));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
