<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DimensionTokenTest extends TestCase
{
    function test1(){
        $nameBit1 = new CheckedNameBitToken("poo");
        $nameBit2 = new CheckedNameBitToken("poo");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $number1 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $number2 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $dimension1 = new DimensionToken($number1, $identifier1);
        $dimension2 = new DimensionToken($number2, $identifier2);

        assertMatch($dimension1, $dimension2);

        assertMatch("-123.456e3poo", (String)$dimension1);
        assertMatch((String)$dimension1, (String)$dimension2);

        assertMatch($number2, $dimension1->getNumber());
        assertMatch($dimension1->getNumber(), $dimension2->getNumber());

        assertMatch($identifier2, $dimension1->getUnit());
        assertMatch($dimension1->getUnit(), $dimension2->getUnit());
    }
}
