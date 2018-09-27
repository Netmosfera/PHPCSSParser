<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class FunctionTokenTest extends TestCase
{
    function test1(){
        $identifier1 = new IdentifierToken(new NameToken([new NameBitToken("linear-gradient")]));
        $identifier2 = new IdentifierToken(new NameToken([new NameBitToken("linear-gradient")]));
        $object1 = new FunctionToken($identifier1);
        $object2 = new FunctionToken($identifier2);

        assertMatch($object1, $object2);

        assertMatch("linear-gradient(", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($identifier2, $object1->getIdentifier());
        assertMatch($object1->getIdentifier(), $object2->getIdentifier());
    }
}
