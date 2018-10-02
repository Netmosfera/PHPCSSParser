<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Operators;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Operators\CheckedDelimiterToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DelimiterTokenTest extends TestCase
{
    public function test1(){
        $delimiter1 = new CheckedDelimiterToken("@");
        $delimiter2 = new CheckedDelimiterToken("@");
        assertMatch($delimiter1, $delimiter2);
        assertMatch((String)$delimiter1, "@");
    }
}
