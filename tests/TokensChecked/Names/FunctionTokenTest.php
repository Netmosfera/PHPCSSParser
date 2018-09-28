<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class FunctionTokenTest extends TestCase
{
    function test1(){
        $nameBit1 = new CheckedNameBitToken("linear-gradient");
        $nameBit2 = new CheckedNameBitToken("linear-gradient");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $object1 = new FunctionToken($identifier1);
        $object2 = new FunctionToken($identifier2);

        assertMatch($object1, $object2);

        assertMatch("linear-gradient(", (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($identifier2, $object1->getIdentifier());
        assertMatch($object1->getIdentifier(), $object2->getIdentifier());
    }
}
