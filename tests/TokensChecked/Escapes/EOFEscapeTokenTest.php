<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class EOFEscapeTokenTest extends TestCase
{
    function test1(){
        $object1 = new EOFEscapeToken();
        $object2 = new EOFEscapeToken();

        assertMatch($object1, $object2);

        assertMatch("\\", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch("", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());
    }
}
