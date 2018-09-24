<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscape;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedEscapeSet;
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
class CodePointEscapeTest extends TestCase
{
    function data1(){
        $set = getEncodedEscapeSet();
        return cartesianProduct(getCodePointsFromRanges($set));
    }

    /** @dataProvider data1 */
    function test1(String $codePoint){
        $object1 = new CheckedCodePointEscape($codePoint);
        $object2 = new CheckedCodePointEscape($codePoint);

        assertMatch($object1, $object2);

        assertMatch("\\" . $codePoint, (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($codePoint, $object1->getCodePoint());
        assertMatch($object1->getCodePoint(), $object2->getCodePoint());

        assertMatch($codePoint, $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        $set = new CompressedCodePointSet();
        $set->removeAll(getEncodedEscapeSet());
        $seqs = getCodePointsFromRanges($set);
        $seqs[] = "";
        $seqs[] = "xx";
        $seqs[] = "\u{2764}\u{2764}";
        return cartesianProduct($seqs);
    }

    /** @dataProvider data2 */
    function test2(String $cp){
        assertThrowsType(InvalidToken::CLASS, function() use($cp){
            new CheckedCodePointEscape($cp);
        });
    }
}
