<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Numbers;

use Netmosfera\PHPCSSAST\Tokens\Numbers\NumberToken;
use Netmosfera\PHPCSSAST\Tokens\Numbers\PercentageToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class PercentageTokenTest extends TestCase
{
    public function test1(){
        $number1 = new NumberToken("-", "123", "456", "e", "", "3");
        $number2 = new NumberToken("-", "123", "456", "e", "", "3");
        $percentage1 = new PercentageToken($number1);
        $percentage2 = new PercentageToken($number2);
        assertMatch($percentage1, $percentage2);
        assertMatch((String)$percentage1, "-123.456e3%");
        assertMatch($percentage1->number(), $number2);
    }
}
