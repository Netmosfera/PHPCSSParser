<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\Tokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\match;
use function Netmosfera\PHPCSSAST\Tokenizer\eatNumericToken;
use Netmosfera\PHPCSSAST\Tokens\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\NumberToken;
use Netmosfera\PHPCSSAST\Traverser;
use PHPUnit\Framework\TestCase;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class eatNumericTokenTest extends TestCase
{
    function test(){
        $prefix = "sdofij2oi3j";

        $sign = "+";
        $wholes = "2398";
        $decimals = "23";
        $eLetter = "e";
        $eSign = "-";
        $eExponent = "28";

        $unitString = "fu__s-given";

        $number = $sign . $wholes . "." . $decimals . $eLetter . $eSign . $eExponent;

        $rest = "@sdf08u23oijsdf";

        $expectedNumber = new NumberToken($sign, $wholes, $decimals, $eLetter, $eSign, $eExponent);

        $unit = new IdentifierToken([$unitString]);
        $t = new Traverser($prefix . $number . $unitString . $rest, TRUE);
        $t->eatStr($prefix);
        self::assertTrue(match(eatNumericToken($t), new DimensionToken($expectedNumber, $unit)));
        self::assertTrue(match($t->eatAll(), $rest));
    }
}
