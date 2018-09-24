<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Operators;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Operators\DelimiterToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DelimiterTokenTest extends TestCase
{
    function test1(){
        $object1 = new DelimiterToken("@");
        $object2 = new DelimiterToken("@");

        assertMatch($object1, $object2);

        assertMatch("@", (String)$object1);
        assertMatch((String)$object1, (String)$object2);
    }
}
