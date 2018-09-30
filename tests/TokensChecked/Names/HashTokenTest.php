<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedHashToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class HashTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new CheckedNameBitToken("BADA55");
        $nameBit2 = new CheckedNameBitToken("BADA55");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $hash1 = new CheckedHashToken($name1);
        $hash2 = new CheckedHashToken($name2);

        assertMatch($hash1, $hash2);

        assertMatch("#BADA55", (String)$hash1);
        assertMatch((String)$hash1, (String)$hash2);

        assertMatch($name2, $hash1->getName());
        assertMatch($hash1->getName(), $hash2->getName());
    }
}
