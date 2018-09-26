<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscape;
use Netmosfera\PHPCSSAST\Tokens\Escapes\HexEscape;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\FunctionToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

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
                new CodePointEscape("@"),
                new NameBitToken("B"),
                new HexEscape("FFAACC", new WhitespaceToken(" ")),
                new NameBitToken("C"),
                new HexEscape("FFAA", new WhitespaceToken("\t")),
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
