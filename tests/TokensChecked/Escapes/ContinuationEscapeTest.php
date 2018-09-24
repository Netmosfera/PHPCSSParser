<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscape;
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
class ContinuationEscapeTest extends TestCase
{
    function data1(){
        return cartesianProduct(getNewlineSeqsSet());
    }

    /** @dataProvider data1 */
    function test1(String $newline){
        $object1 = new CheckedContinuationEscape($newline);
        $object2 = new CheckedContinuationEscape($newline);

        assertMatch($object1, $object2);

        assertMatch($newline, $object1->getCodePoint());
        assertMatch($object1->getCodePoint(), $object2->getCodePoint());

        assertMatch("", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());

        assertMatch("\\" . $newline, (String)$object1);
        assertMatch((String)$object1, (String)$object2);
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
            new CheckedContinuationEscape($newline);
        });
    }
}
