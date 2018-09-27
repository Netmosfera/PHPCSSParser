<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class HashTokenTest extends TestCase
{
    function test1(){
        $name1 = new NameToken([new NameBitToken("BADA55")]);
        $name2 = new NameToken([new NameBitToken("BADA55")]);
        $object1 = new HashToken($name1);
        $object2 = new HashToken($name2);

        assertMatch($object1, $object2);

        assertMatch("#BADA55", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($name2, $object1->getName());
        assertMatch($object1->getName(), $object2->getName());
    }
}
