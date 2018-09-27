<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class NameTokenTest extends TestCase
{
    function test1(){

        $pieces = [];
        for($i = 0; $i < 2; $i++){
            $pieces[$i] = [
                new NameBitToken("A"),
                new EncodedCodePointEscapeToken("@"),
                new NameBitToken("B"),
                new CodePointEscapeToken("FFAACC", new WhitespaceToken(" ")),
                new NameBitToken("C"),
                new CodePointEscapeToken("FFAA", new WhitespaceToken("\t")),
                new NameBitToken("D\0D"),
            ];
        }

        [$pieces1, $pieces2] = $pieces;

        $object1 = new NameToken($pieces1);
        $object2 = new NameToken($pieces2);

        assertMatch($object1, $object2);

        assertMatch(implode("", $pieces1), (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch("A@B\u{FFFD}C\u{FFAA}D\u{FFFD}D", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());

        assertMatch($pieces1, $object1->getPieces());
        assertMatch($object1->getPieces(), $object2->getPieces());
    }
}
