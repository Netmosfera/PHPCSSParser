<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSASTDev\Data\CompressedCodePointSet;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTDev\Data\cp;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class NameBitTokenTest extends TestCase
{
    public function data1(){
        $set = getNameItemsSet();
        $set->remove(cp("\0"));
        foreach(getSampleCodePointsFromRanges($set) as $codePoint){
            yield [$codePoint, $codePoint];
            yield ["aaa" . $codePoint, "aaa" . $codePoint];
            yield [$codePoint . "aaa", $codePoint . "aaa"];
            yield ["aaa" . $codePoint . "aaa", "aaa" . $codePoint . "aaa"];
        }
        yield ["\0", SpecData::REPLACEMENT_CHARACTER];
        yield ["\0", SpecData::REPLACEMENT_CHARACTER];
        yield ["aaa\0", "aaa" . SpecData::REPLACEMENT_CHARACTER];
        yield ["\0aaa", SpecData::REPLACEMENT_CHARACTER . "aaa"];
        yield ["aaa\0aaa", "aaa" . SpecData::REPLACEMENT_CHARACTER . "aaa"];
    }

    /** @dataProvider data1 */
    public function test1(String $value, String $intendedValue){
        $nameBit1 = new CheckedNameBitToken($value);
        $nameBit2 = new CheckedNameBitToken($value);
        assertMatch($nameBit1, $nameBit2);
        assertMatch((String)$nameBit1, $value);
        assertMatch($nameBit1->intendedValue(), $intendedValue);
    }

    public function data2(){
        $set = new CompressedCodePointSet();
        $set->selectAll();
        $set->removeAll(getNameItemsSet());
        $restricted = getSampleCodePointsFromRanges($set);
        foreach($restricted as $codePoint){
            yield [$codePoint];
            yield ["aaa" . $codePoint];
            yield [$codePoint . "aaa"];
            yield ["aaa" . $codePoint . "aaa"];
        }
    }

    /** @dataProvider data2 */
    public function test2(String $value){
        assertThrowsType(InvalidToken::CLASS, function() use($value){
            new CheckedNameBitToken($value);
        });
    }
}
