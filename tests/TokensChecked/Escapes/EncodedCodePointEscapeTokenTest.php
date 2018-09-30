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
class EncodedCodePointEscapeTokenTest extends TestCase
{
    public function data1(){
        $set = getEncodedCodePointEscapeSet();
        return cartesianProduct(getCodePointsFromRanges($set));
    }

    /** @dataProvider data1 */
    public function test1(String $codePoint){
        $encodedCPEscape1 = new CheckedEncodedCodePointEscapeToken($codePoint);
        $encodedCPEscape2 = new CheckedEncodedCodePointEscapeToken($codePoint);

        assertMatch($encodedCPEscape1, $encodedCPEscape2);

        assertMatch("\\" . $codePoint, (String)$encodedCPEscape1);
        assertMatch((String)$encodedCPEscape1, (String)$encodedCPEscape2);

        assertMatch($codePoint, $encodedCPEscape1->getValue());
        assertMatch($encodedCPEscape1->getValue(), $encodedCPEscape2->getValue());
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
