<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDCTokenTest extends TestCase
{
    function test1(){
        $object1 = new CDCToken();
        $object2 = new CDCToken();

        assertMatch($object1, $object2);

        assertMatch("-->", (String)$object1);
        assertMatch((String)$object1, (String)$object2);
    }
}
