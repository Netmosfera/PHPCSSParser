<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Escapes;

use function array_shift;
use function iterator_to_array;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getWhitespaceSeqsSet;
use Netmosfera\PHPCSSAST\Tokens\Escapes\CodePointEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\SpecData;
use PHPUnit\Framework\TestCase;
use IntlChar;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CodePointEscapeTokenTest extends TestCase
{
    public function data1(){
        $possibleDigits = str_split("AaBbCcDdEeFf1234567890", 1);

        $digit = function() use(&$possibleDigits){
            $digit = array_shift($possibleDigits);
            $possibleDigits[] = $digit;
            return $digit;
        };

        $hexDigits[] = $digit();
        $hexDigits[] = $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit();
        $hexDigits[] = $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit();
        $hexDigits[] = $digit() . $digit() . $digit() . $digit() . $digit() . $digit();

        $whitespaces = iterator_to_array(getWhitespaceSeqsSet(), FALSE);
        $whitespaces[] = NULL;

        return cartesianProduct($hexDigits, $whitespaces);
    }

    /** @dataProvider data1 */
    public function test1(String $hexDigits, ?String $ws){
        $ws1 = $ws === NULL ? NULL : new WhitespaceToken($ws);
        $ws2 = $ws === NULL ? NULL : new WhitespaceToken($ws);
        $escape1 = new CodePointEscapeToken($hexDigits, $ws1);
        $escape2 = new CodePointEscapeToken($hexDigits, $ws2);
        assertMatch($escape1, $escape2);
        $codePoint = IntlChar::chr($escape1->integerValue());
        $intendedValue = $codePoint ?? SpecData::REPLACEMENT_CHARACTER;
        $isValid = $codePoint !== NULL;
        assertMatch((String)$escape1, "\\" . $hexDigits . $ws);
        assertMatch($escape1->hexDigits(), $hexDigits);
        assertMatch($escape1->integerValue(), hexdec($hexDigits));
        assertMatch($escape1->terminator(), $ws2);
        assertMatch($escape1->intendedValue(), $intendedValue);
        assertMatch($escape1->isValid(), $isValid);
    }
}
