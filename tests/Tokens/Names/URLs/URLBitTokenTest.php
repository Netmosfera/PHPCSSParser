<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names\URLs;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getURLTokenBitSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class URLBitTokenTest extends TestCase
{
    public function data1(){
        $set = getURLTokenBitSet();
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
        $URLBit1 = new URLBitToken($value);
        $URLBit2 = new URLBitToken($value);
        assertMatch($URLBit1, $URLBit2);
        assertMatch((String)$URLBit1, $value);
        assertMatch($URLBit1->intendedValue(), $intendedValue);
    }
}
