<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class NumberTokenTest extends TestCase
{
    function test(){
        self::assertTrue(TRUE);
    }

    function xtest1(String $sign, String $wholes, String $decimals, String $e){
        $object1 = new NumberToken("-", "123", "456", "e", "-", "3");
        $object2 = new NumberToken("-", "123", "456", "e", "-", "3");

        assertMatch($object1, $object2);

        assertMatch("-123.456e-3", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch("123", $object1->getWholes());
        assertMatch($object1->getWholes(), $object2->getWholes());

        assertMatch("456", $object1->getDecimals());
        assertMatch($object1->getDecimals(), $object2->getDecimals());

        assertMatch("456", $object1->getELetter());
        assertMatch($object1->getELetter(), $object2->getELetter());
    }
}
