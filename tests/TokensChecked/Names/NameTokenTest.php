<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCPEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCPEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Names\CheckedNameToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class NameTokenTest extends TestCase
{
    public function test1(){
        $pieces = [];
        for($i = 0; $i < 2; $i++){
            $pieces[$i] = [
                new CheckedNameBitToken("A"),
                new CheckedEncodedCPEscapeToken("@"),
                new CheckedNameBitToken("B"),
                new CheckedCPEscapeToken("FFAACC", new CheckedWhitespaceToken(" ")),
                new CheckedNameBitToken("C"),
                new CheckedCPEscapeToken("FFAA", new CheckedWhitespaceToken("\t")),
                new CheckedNameBitToken("D\0D"),
            ];
        }

        [$pieces1, $pieces2] = $pieces;

        $name1 = new CheckedNameToken($pieces1);
        $name2 = new CheckedNameToken($pieces2);

        assertMatch($name1, $name2);

        assertMatch(implode("", $pieces1), (String)$name1);
        assertMatch((String)$name1, (String)$name2);

        assertMatch("A@B\u{FFFD}C\u{FFAA}D\u{FFFD}D", $name1->getValue());
        assertMatch($name1->getValue(), $name2->getValue());

        assertMatch($pieces1, $name1->getPieces());
        assertMatch($name1->getPieces(), $name2->getPieces());
    }

    // @TODO test invalid cases
}
