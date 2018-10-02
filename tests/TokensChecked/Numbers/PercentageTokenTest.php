<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Numbers;

use function Netmosfera\PHPCSSASTTests\assertMatch;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedPercentageToken;
use Netmosfera\PHPCSSAST\TokensChecked\Numbers\CheckedNumberToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class PercentageTokenTest extends TestCase
{
    public function test1(){
        $number1 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $number2 = new CheckedNumberToken("-", "123", "456", "e", "", "3");
        $percentage1 = new CheckedPercentageToken($number1);
        $percentage2 = new CheckedPercentageToken($number2);
        assertMatch($percentage1, $percentage2);
        assertMatch((String)$percentage1, "-123.456e3%");
        assertMatch($percentage1->number(), $number2);
    }
}
