<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names\URLs;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getBadURLRemnantsBitSet;
use function Netmosfera\PHPCSSASTDev\Data\cp;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class BadURLRemnantsBitTokenTest extends TestCase
{
    public function data1(){
        $set = getBadURLRemnantsBitSet();
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
        $badURLRemnantsBit1 = new BadURLRemnantsBitToken($value);
        $badURLRemnantsBit2 = new BadURLRemnantsBitToken($value);
        assertMatch($badURLRemnantsBit1, $badURLRemnantsBit2);
        assertMatch((String)$badURLRemnantsBit1, $value);
        assertMatch($badURLRemnantsBit1->intendedValue(), $intendedValue);
    }
}
