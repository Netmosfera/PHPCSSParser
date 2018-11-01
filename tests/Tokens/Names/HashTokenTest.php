<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\Names\HashToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class HashTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new NameBitToken("BADA55");
        $nameBit2 = new NameBitToken("BADA55");
        $name1 = new NameToken([$nameBit1]);
        $name2 = new NameToken([$nameBit2]);
        $hash1 = new HashToken($name1);
        $hash2 = new HashToken($name2);
        assertMatch($hash1, $hash2);
        assertMatch((String)$hash1, "#BADA55");
        assertMatch($hash1->name(), $name2);
    }
}
