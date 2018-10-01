<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class AtKeywordTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new CheckedNameBitToken("import");
        $nameBit2 = new CheckedNameBitToken("import");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $atKeyword1 = new CheckedAtKeywordToken($identifier1);
        $atKeyword2 = new CheckedAtKeywordToken($identifier2);
        assertMatch($atKeyword1, $atKeyword2);
        assertMatch((String)$atKeyword1, "@import");
        assertMatch($atKeyword1->identifier(), $identifier2);
    }
}
