<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\DelimiterToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class DelimiterTokenTest extends TestCase
{
    public function test1(){
        $delimiter1 = new DelimiterToken("@");
        $delimiter2 = new DelimiterToken("@");
        assertMatch($delimiter1, $delimiter2);
        assertMatch((String)$delimiter1, "@");
    }
}
