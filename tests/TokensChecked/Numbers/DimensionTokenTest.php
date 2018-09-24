<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
        $dimension1 = new IdentifierToken(new NameToken(["poo"]));
        $dimension2 = new IdentifierToken(new NameToken(["poo"]));
        $number1 = new NumberToken("-", "123", "456", "e", "", "3");
        $number2 = new NumberToken("-", "123", "456", "e", "", "3");
        $object1 = new DimensionToken($number1, $dimension1);
        $object2 = new DimensionToken($number2, $dimension2);

        assertMatch($object1, $object2);

        assertMatch("-123.456e3poo", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($number2, $object1->getNumber());
        assertMatch($object1->getNumber(), $object2->getNumber());

        assertMatch($dimension2, $object1->getUnit());
        assertMatch($object1->getUnit(), $object2->getUnit());
    }
}
