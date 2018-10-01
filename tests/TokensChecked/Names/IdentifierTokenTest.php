<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 * #2 | test invalid
 */
class IdentifierTokenTest extends TestCase
{
    public function data1(){
        // --
        $names[] = [new CheckedNameBitToken("--")];

        // - followed by valid escape
        $names[] = [
            new CheckedNameBitToken("-"),
            new CheckedEncodedCodePointEscapeToken("x")
        ];

        // - followed by name-start code point
        $names[] = [new CheckedNameBitToken("-a")];
        $names[] = [new CheckedNameBitToken("-A")];
        $names[] = [new CheckedNameBitToken("-_")];
        $names[] = [new CheckedNameBitToken("-\u{2764}")];

        // a name-start code point
        $names[] = [new CheckedNameBitToken("a")];
        $names[] = [new CheckedNameBitToken("A")];
        $names[] = [new CheckedNameBitToken("_")];
        $names[] = [new CheckedNameBitToken("\u{2764}")];

        // a valid escape
        $names[] = [new CheckedEncodedCodePointEscapeToken("x")];

        return cartesianProduct($names);
    }

    /** @dataProvider data1 */
    public function test1(Array $name){
        $name1 = new CheckedNameToken($name);
        $name2 = new CheckedNameToken($name);
        $identifier1 = new CheckedIdentifierToken($name1);
        $identifier2 = new CheckedIdentifierToken($name2);
        assertMatch($identifier1, $identifier2);
        assertMatch((String)$identifier2, (String)$identifier1);
        assertMatch($identifier2->name(), $identifier1->name());
    }

    // @TODO invalids
}
