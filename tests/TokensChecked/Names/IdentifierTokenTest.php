<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class IdentifierTokenTest extends TestCase
{
    function data1(){
        $names[] = [new CheckedNameBitToken("--")];

        $names[] = [new CheckedNameBitToken("-"), new CheckedEncodedCodePointEscapeToken("x")];

        $names[] = [new CheckedNameBitToken("-a")];
        $names[] = [new CheckedNameBitToken("-A")];
        $names[] = [new CheckedNameBitToken("-_")];
        $names[] = [new CheckedNameBitToken("-\u{2764}")];

        $names[] = [new CheckedNameBitToken("a")];
        $names[] = [new CheckedNameBitToken("A")];
        $names[] = [new CheckedNameBitToken("_")];
        $names[] = [new CheckedNameBitToken("\u{2764}")];

        $names[] = [new CheckedEncodedCodePointEscapeToken("x")];

        return cartesianProduct($names);
    }

    /** @dataProvider data1 */
    function test1(Array $name){
        $name1 = new CheckedNameToken($name);
        $name2 = new CheckedNameToken($name);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);

        assertMatch($identifier1, $identifier2);

        assertMatch((String)$name1, (String)$identifier1);
        assertMatch((String)$identifier1, (String)$identifier2);

        assertMatch($name1, $identifier1->getName());
        assertMatch($identifier1->getName(), $identifier2->getName());
    }

    // @TODO invalids
}
