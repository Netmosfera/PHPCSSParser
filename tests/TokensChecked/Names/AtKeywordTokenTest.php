<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class AtKeywordTokenTest extends TestCase
{
    function test1(){
        $identifier1 = new IdentifierToken(new NameToken(["import"]));
        $identifier2 = new IdentifierToken(new NameToken(["import"]));
        $object1 = new AtKeywordToken($identifier1);
        $object2 = new AtKeywordToken($identifier2);

        assertMatch($object1, $object2);

        assertMatch("@import", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($identifier2, $object1->getIdentifier());
        assertMatch($object1->getIdentifier(), $object2->getIdentifier());
    }
}
