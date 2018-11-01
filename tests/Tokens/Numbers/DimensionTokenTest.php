<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Numbers;

use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\DimensionToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DimensionTokenTest extends TestCase
{
    public function test1(){
        $nameBit1 = new NameBitToken("iau");
        $nameBit2 = new NameBitToken("iau");
        $name1 = new NameToken([$nameBit1]);
        $name2 = new NameToken([$nameBit2]);
        $identifier1 = new IdentifierToken($name1);
        $identifier2 = new IdentifierToken($name2);
        $number1 = new NumberToken("-", "123", "456", "e", "", "3");
        $number2 = new NumberToken("-", "123", "456", "e", "", "3");
        $dimension1 = new DimensionToken($number1, $identifier1);
        $dimension2 = new DimensionToken($number2, $identifier2);
        assertMatch($dimension1, $dimension2);
        assertMatch((String)$dimension1, "-123.456e3iau");
        assertMatch($dimension1->number(), $number2);
        assertMatch($dimension1->unit(), $identifier2);
    }
}
