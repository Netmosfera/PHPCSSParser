<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
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
        $function1 = new FunctionToken($identifier1);
        $function2 = new FunctionToken($identifier2);

        assertMatch($function1, $function2);

        assertMatch("linear-gradient(", (String)$function1);
        assertMatch((String)$function1, (String)$function2);

        assertMatch($identifier2, $function1->getIdentifier());
        assertMatch($function1->getIdentifier(), $function2->getIdentifier());
    }
}
