<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSets\getEncodedCodePointEscapeSet;
use function Netmosfera\PHPCSSASTTests\getSampleCodePointsFromRanges;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class EncodedCodePointEscapeTokenTest extends TestCase
{
    public function data1(){
        $set = getEncodedCodePointEscapeSet();
        return cartesianProduct(getSampleCodePointsFromRanges($set));
    }

    /** @dataProvider data1 */
    public function test1(String $codePoint){
        $escape1 = new EncodedCodePointEscapeToken($codePoint);
        $escape2 = new EncodedCodePointEscapeToken($codePoint);
        assertMatch($escape1, $escape2);
        assertMatch((String)$escape1, "\\" . $codePoint);
        if($codePoint === "\0"){
            $intendedValue = SpecData::REPLACEMENT_CHARACTER;
        }else{
            $intendedValue = $codePoint;
        }
        assertMatch($escape1->intendedValue(), $intendedValue);
    }
}
