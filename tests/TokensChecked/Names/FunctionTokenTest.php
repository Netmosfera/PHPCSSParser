<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedFunctionToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class FunctionTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new CheckedNameBitToken("linear-gradient");
        $nameBit2 = new CheckedNameBitToken("linear-gradient");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $function1 = new CheckedFunctionToken($identifier1);
        $function2 = new CheckedFunctionToken($identifier2);
        assertMatch($function1, $function2);
        assertMatch((String)$function1, "linear-gradient(");
        assertMatch($function1->identifier(), $identifier2);
    }
}
