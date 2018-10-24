<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedCodePointEscapeSet;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class EncodedCodePointEscapeTokenTest extends TestCase
{
    public function data1(){
        $set = getEncodedCodePointEscapeSet();
        return cartesianProduct(getSampleCodePointsFromRanges($set));
    }

    /** @dataProvider data1 */
    public function test1(String $codePoint){
        $escape1 = new CheckedEncodedCodePointEscapeToken($codePoint);
        $escape2 = new CheckedEncodedCodePointEscapeToken($codePoint);
        assertMatch($escape1, $escape2);
        assertMatch((String)$escape1, "\\" . $codePoint);
        if($codePoint === "\0"){
            $intendedValue = SpecData::$instance->REPLACEMENT_CHARACTER;
        }else{
            $intendedValue = $codePoint;
        }
        assertMatch($escape1->intendedValue(), $intendedValue);
    }

    public function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getEncodedCodePointEscapeSet());
        $seqs = getSampleCodePointsFromRanges($set);
        $seqs[] = ""; // invalid length
        $seqs[] = "xx"; // invalid length
        $seqs[] = "\u{2764}\u{2764}"; // invalid length
        return cartesianProduct($seqs);
    }

    /** @dataProvider data2 */
    public function test2(String $codePoint){
        assertThrowsType(InvalidToken::CLASS, function() use($codePoint){
            new CheckedEncodedCodePointEscapeToken($codePoint);
        });
    }
}
