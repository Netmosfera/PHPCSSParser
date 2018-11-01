<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getNameItemsSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;

/**
 * Tests in this file:
 *
 * #1 | test getters
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
        $nameBit1 = new NameBitToken($value);
        $nameBit2 = new NameBitToken($value);
        assertMatch($nameBit1, $nameBit2);
        assertMatch((String)$nameBit1, $value);
        assertMatch($nameBit1->intendedValue(), $intendedValue);
    }
}
