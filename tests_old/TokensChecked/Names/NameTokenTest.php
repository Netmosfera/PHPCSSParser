<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
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
            $ws1 = new CheckedWhitespaceToken(" ");
            $ws2 = new CheckedWhitespaceToken("\t");
            $pieces[$i] = [
                new CheckedNameBitToken("A"),
                new CheckedEncodedCodePointEscapeToken("@"),
                new CheckedNameBitToken("B"),
                new CheckedCodePointEscapeToken("FFAACC", $ws1),
                new CheckedNameBitToken("C"),
                new CheckedCodePointEscapeToken("FFAA", $ws2),
                new CheckedNameBitToken("D\0D"),
            ];
        }

        [$pieces1, $pieces2] = $pieces;

        $name1 = new CheckedNameToken($pieces1);
        $name2 = new CheckedNameToken($pieces2);

        assertMatch($name1, $name2);

        assertMatch(implode("", $pieces1), (String)$name1);
        assertMatch((String)$name1, (String)$name2);

        assertMatch("A@B\u{FFFD}C\u{FFAA}D\u{FFFD}D", $name1->intendedValue());
        assertMatch($name1->intendedValue(), $name2->intendedValue());

        assertMatch($pieces1, $name1->pieces());
        assertMatch($name1->pieces(), $name2->pieces());
    }

    // @TODO test invalid cases
}
