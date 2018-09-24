<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class PercentageTokenTest extends TestCase
{
    function test1(){
        $number1 = new NumberToken("-", "123", "456", "e", "", "3");
        $number2 = new NumberToken("-", "123", "456", "e", "", "3");
        $object1 = new PercentageToken($number1);
        $object2 = new PercentageToken($number2);

        assertMatch($object1, $object2);

        assertMatch("-123.456e3%", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($number2, $object1->getNumber());
        assertMatch($object1->getNumber(), $object2->getNumber());
    }
}
