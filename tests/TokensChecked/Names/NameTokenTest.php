<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
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
                new CheckedNameBitToken("A"),
                new CheckedEncodedCodePointEscapeToken("@"),
                new CheckedNameBitToken("B"),
                new CheckedCodePointEscapeToken("FFAACC", new CheckedWhitespaceToken(" ")),
                new CheckedNameBitToken("C"),
                new CheckedCodePointEscapeToken("FFAA", new CheckedWhitespaceToken("\t")),
                new CheckedNameBitToken("D\0D"),
            ];
        }

        [$pieces1, $pieces2] = $pieces;

        $object1 = new CheckedNameToken($pieces1);
        $object2 = new CheckedNameToken($pieces2);

        assertMatch($object1, $object2);

        assertMatch(implode("", $pieces1), (String)$object1);
        assertMatch((String)$object1, (String)$object2);

        assertMatch("A@B\u{FFFD}C\u{FFAA}D\u{FFFD}D", $object1->getValue());
        assertMatch($object1->getValue(), $object2->getValue());

        assertMatch($pieces1, $object1->getPieces());
        assertMatch($object1->getPieces(), $object2->getPieces());
    }
}
