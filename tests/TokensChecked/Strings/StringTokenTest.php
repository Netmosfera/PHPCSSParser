<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Strings;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class StringTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(["\"", "'"], [TRUE, FALSE]);
    }

    /** @dataProvider data1 */
    public function test1(String $delimiter, Bool $EOFTerminated){
        $pieces = [];
        for($i = 0; $i < 2; $i++){
            $whitespace1 = new CheckedWhitespaceToken(" ");
            $whitespace2 = new CheckedWhitespaceToken("\t");
            $pieces[$i] = [
                new CheckedStringBitToken("A"),
                new CheckedEncodedCodePointEscapeToken("@"),
                new CheckedStringBitToken("B"),
                new CheckedCodePointEscapeToken("FFAACC", $whitespace1),
                new CheckedStringBitToken("C"),
                new CheckedCodePointEscapeToken("FFAA", $whitespace2),
                new CheckedEncodedCodePointEscapeToken("\0"),
                new CheckedStringBitToken("D\0D"),
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
        $string1 = new CheckedStringToken($delimiter, $pieces1, $EOFTerminated);
        $string2 = new CheckedStringToken($delimiter, $pieces2, $EOFTerminated);
        assertMatch($string1, $string2);
        $stringEnd = $EOFTerminated ? "" : $delimiter;
        assertMatch((String)$string1, $delimiter . implode("", $pieces1) . $stringEnd);
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->EOFTerminated(), $EOFTerminated);
        assertMatch($string1->pieces(), $pieces2);
    }
}
