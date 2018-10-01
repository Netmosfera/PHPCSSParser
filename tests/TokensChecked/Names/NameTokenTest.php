<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Names;

use Netmosfera\PHPCSSAST\SpecData;
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
            $whitespace1 = new CheckedWhitespaceToken(" ");
            $whitespace2 = new CheckedWhitespaceToken("\t");
            $pieces[$i] = [
                new CheckedNameBitToken("A"),
                new CheckedEncodedCodePointEscapeToken("@"),
                new CheckedNameBitToken("B"),
                new CheckedCodePointEscapeToken("FFAACC", $whitespace1),
                new CheckedNameBitToken("C"),
                new CheckedCodePointEscapeToken("FFAA", $whitespace2),
                new CheckedEncodedCodePointEscapeToken("\0"),
                new CheckedNameBitToken("D\0D"),
            ];
        }

        $intendedValue  = "A";
        $intendedValue .= "@";
        $intendedValue .= "B";
        $intendedValue .= SpecData::REPLACEMENT_CHARACTER;
        $intendedValue .= "C";
        $intendedValue .= "\u{FFAA}";
        $intendedValue .= SpecData::REPLACEMENT_CHARACTER;
        $intendedValue .= "D" . SpecData::REPLACEMENT_CHARACTER . "D";

        [$pieces1, $pieces2] = $pieces;
        $name1 = new CheckedNameToken($pieces1);
        $name2 = new CheckedNameToken($pieces2);
        assertMatch($name1, $name2);
        assertMatch((String)$name1, implode("", $pieces1));
        assertMatch($name1->intendedValue(), $intendedValue);
        assertMatch($name1->pieces(), $pieces2);
    }

    // @TODO test invalid cases
}
