<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

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

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class ContinuationEscapeTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(getNewlineSeqsSet());
    }

    /** @dataProvider data1 */
    public function test1(String $newline){
        $continuation1 = new CheckedContinuationEscapeToken($newline);
        $continuation2 = new CheckedContinuationEscapeToken($newline);

        assertMatch($continuation1, $continuation2);

        assertMatch("\\" . $newline, (String)$continuation1);
        assertMatch((String)$continuation1, (String)$continuation2);

        assertMatch($newline, $continuation1->codePoint());
        assertMatch($continuation1->codePoint(), $continuation2->codePoint());

        assertMatch("", $continuation1->intendedValue());
        assertMatch($continuation1->intendedValue(), $continuation2->intendedValue());
    }

    public function data2(){
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
    public function test2(String $newline){
        assertThrowsType(InvalidToken::CLASS, function() use($newline){
            new CheckedContinuationEscapeToken($newline);
        });
    }
}
