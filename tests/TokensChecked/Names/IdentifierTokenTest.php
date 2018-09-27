<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedIdentifierToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCommentToken;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

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
        $names[] = [new NameBitToken("--")];

        $names[] = [new NameBitToken("-"), new EncodedCodePointEscapeToken("x")];

        $names[] = [new NameBitToken("-a")];
        $names[] = [new NameBitToken("-A")];
        $names[] = [new NameBitToken("-_")];
        $names[] = [new NameBitToken("-\u{2764}")];

        $names[] = [new NameBitToken("a")];
        $names[] = [new NameBitToken("A")];
        $names[] = [new NameBitToken("_")];
        $names[] = [new NameBitToken("\u{2764}")];

        $names[] = [new EncodedCodePointEscapeToken("x")];

        return cartesianProduct($names);
    }

    /** @dataProvider data1 */
    function test1(Array $name){
        $name1 = new NameToken($name);
        $name2 = new NameToken($name);
        $object1 = new CheckedIdentifierToken($name1);
        $object2 = new CheckedIdentifierToken($name2);

        assertMatch($object1, $object2);

        assertMatch((String)$name1, (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch($name1, $object1->getName());
        assertMatch($object1->getName(), $object2->getName());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function data2(){
        return cartesianProduct([]);
    }

    /** @dataProvider data2 */
    function xtest2(){
        assertThrowsType(InvalidToken::CLASS, function(){
            new CheckedCommentToken($comment, FALSE);
        });
    }
}
