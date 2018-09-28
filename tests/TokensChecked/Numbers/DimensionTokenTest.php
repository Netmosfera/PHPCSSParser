<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedDimensionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DimensionTokenTest extends TestCase
{
    function test1(){
        $nameBit1 = new CheckedNameBitToken("iau");
        $nameBit2 = new CheckedNameBitToken("iau");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $number1 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $number2 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $dimension1 = new CheckedDimensionToken($number1, $identifier1);
        $dimension2 = new CheckedDimensionToken($number2, $identifier2);

        assertMatch($dimension1, $dimension2);

        assertMatch("-123.456e3iau", (String)$dimension1);
        assertMatch((String)$dimension1, (String)$dimension2);

        assertMatch($number2, $dimension1->getNumber());
        assertMatch($dimension1->getNumber(), $dimension2->getNumber());

        assertMatch($identifier2, $dimension1->getUnit());
        assertMatch($dimension1->getUnit(), $dimension2->getUnit());
    }
}
