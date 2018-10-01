<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedCodePointEscapeSet;
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
class EncodedCPEscapeTokenTest extends TestCase
{
    public function data1(){
        $set = getEncodedCodePointEscapeSet();
        return cartesianProduct(getCodePointsFromRanges($set));
    }

    /** @dataProvider data1 */
    public function test1(String $codePoint){
        $CPEscape1 = new CheckedEncodedCodePointEscapeToken($codePoint);
        $CPEscape2 = new CheckedEncodedCodePointEscapeToken($codePoint);

        assertMatch($CPEscape1, $CPEscape2);

        assertMatch("\\" . $codePoint, (String)$CPEscape1);
        assertMatch((String)$CPEscape1, (String)$CPEscape2);

        assertMatch($codePoint, $CPEscape1->intendedValue());
        assertMatch($CPEscape1->intendedValue(), $CPEscape2->intendedValue());
    }

    public function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getEncodedCodePointEscapeSet());
        $seqs = getCodePointsFromRanges($set);
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
