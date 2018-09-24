<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDOTokenTest extends TestCase
{
    function test1(){
        $object1 = new CDOToken();
        $object2 = new CDOToken();

        assertMatch($object1, $object2);

        assertMatch("<!--", (String)$object1);
        assertMatch((String)$object1, (String)$object2);
    }
}
