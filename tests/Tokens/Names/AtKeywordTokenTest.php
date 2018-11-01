<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Names;

use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
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
class AtKeywordTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new NameBitToken("import");
        $nameBit2 = new NameBitToken("import");
        $name1 = new NameToken([$nameBit1]);
        $name2 = new NameToken([$nameBit2]);
        $identifier1 = new IdentifierToken($name1);
        $identifier2 = new IdentifierToken($name2);
        $atKeyword1 = new AtKeywordToken($identifier1);
        $atKeyword2 = new AtKeywordToken($identifier2);
        assertMatch($atKeyword1, $atKeyword2);
        assertMatch((String)$atKeyword1, "@import");
        assertMatch($atKeyword1->identifier(), $identifier2);
    }
}
