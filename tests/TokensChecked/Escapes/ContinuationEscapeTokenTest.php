<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNewlinesSet;
use function Netmosfera\PHPCSSASTTests\getCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class ContinuationEscapeTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(getNewlineSeqsSet());
    }

    /** @dataProvider data1 */
    function test1(String $newline){
        $continuationEscape1 = new CheckedContinuationEscapeToken($newline);
        $continuationEscape2 = new CheckedContinuationEscapeToken($newline);

        assertMatch($continuationEscape1, $continuationEscape2);

        assertMatch("\\" . $newline, (String)$continuationEscape1);
        assertMatch((String)$continuationEscape1, (String)$continuationEscape2);

        assertMatch($newline, $continuationEscape1->getCodePoint());
        assertMatch($continuationEscape1->getCodePoint(), $continuationEscape2->getCodePoint());

        assertMatch("", $continuationEscape1->getValue());
        assertMatch($continuationEscape1->getValue(), $continuationEscape2->getValue());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getNewlinesSet());
        $seqs = getCodePointsFromRanges($set);
        $seqs[] = "\n\n";
        $seqs[] = "f";
        $seqs[] = "F";
        $seqs[] = "5";
        $seqs[] = "";
        return cartesianProduct($seqs);
    }

    /** @dataProvider data2 */
    function test2(String $newline){
        assertThrowsType(InvalidToken::CLASS, function() use($newline){
            new CheckedContinuationEscapeToken($newline);
        });
    }
}
