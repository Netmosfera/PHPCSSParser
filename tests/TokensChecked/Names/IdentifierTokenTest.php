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
        $object1 = new CheckedIdentifierToken($name1);
        $object2 = new CheckedIdentifierToken($name2);

        assertMatch($object1, $object2);

        assertMatch((String)$name1, (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($name1, $object1->getName());
        assertMatch($object1->getName(), $object2->getName());
    }

    // @TODO invalids
}
