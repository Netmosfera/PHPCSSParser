<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class FunctionTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new NameBitToken("linear-gradient");
        $nameBit2 = new NameBitToken("linear-gradient");
        $name1 = new NameToken([$nameBit1]);
        $name2 = new NameToken([$nameBit2]);
        $identifier1 = new IdentifierToken($name1);
        $identifier2 = new IdentifierToken($name2);
        $function1 = new FunctionToken($identifier1);
        $function2 = new FunctionToken($identifier2);
        assertMatch($function1, $function2);
        assertMatch((String)$function1, "linear-gradient(");
        assertMatch($function1->identifier(), $identifier2);
    }
}
