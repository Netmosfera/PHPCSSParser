<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedAtKeywordToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
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
        $nameBit1 = new CheckedNameBitToken("import");
        $nameBit2 = new CheckedNameBitToken("import");
        $name1 = new CheckedNameToken([$nameBit1]);
        $name2 = new CheckedNameToken([$nameBit2]);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        $atKeyword1 = new CheckedAtKeywordToken($identifier1);
        $atKeyword2 = new CheckedAtKeywordToken($identifier2);

        assertMatch($atKeyword1, $atKeyword2);

        assertMatch("@import", (String)$atKeyword1);
        assertMatch((String)$atKeyword1, (String)$atKeyword2);

        assertMatch($identifier2, $atKeyword1->getIdentifier());
        assertMatch($atKeyword1->getIdentifier(), $atKeyword2->getIdentifier());
    }
}
