<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Strings;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEncodedCodePointEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedWhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\assertThrowsType;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class BadStringTokenTest extends TestCase
{
    function data1(){
        return cartesianProduct(["\"", "'"]);
    }

    /** @dataProvider data1 */
    public function test1(String $delimiter){
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
        $string1 = new CheckedBadStringToken($delimiter, $pieces1);
        $string2 = new CheckedBadStringToken($delimiter, $pieces2);
        assertMatch($string1, $string2);
        assertMatch((String)$string1, $delimiter . implode("", $pieces1));
        assertMatch($string1->delimiter(), $delimiter);
        assertMatch($string1->intendedValue(), $intendedValue);
        assertMatch($string1->pieces(), $pieces2);
    }

    public function data2(){
        yield [[
            0 => new CheckedEncodedCodePointEscapeToken("@"),
            1 => new CheckedEncodedCodePointEscapeToken("@"),
            3 => new CheckedEncodedCodePointEscapeToken("@"),
            2 => new CheckedEncodedCodePointEscapeToken("@"),
        ]];
        yield [[
            new stdClass()
        ]];
    }

    /** @dataProvider data2 */
    public function test2(Array $pieces){
        assertThrowsType(TypeError::CLASS, function() use($pieces){
            new CheckedBadStringToken("'", $pieces);
        });
    }

    public function data3(){
        yield [[
            new CheckedEncodedCodePointEscapeToken("@"),
            new StringBitToken("abc"),
            new StringBitToken("def"),
            new CheckedEncodedCodePointEscapeToken("@"),
        ]];
    }

    /** @dataProvider data3 */
    public function test3(Array $pieces){
        assertThrowsType(InvalidToken::CLASS, function() use($pieces){
            new CheckedBadStringToken("'", $pieces);
        });
    }
}
