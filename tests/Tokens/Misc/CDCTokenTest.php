<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDCTokenTest extends TestCase
{
    public function test1(){
        $CDC1 = new CDCToken();
        $CDC2 = new CDCToken();
        assertMatch($CDC1, $CDC2);
        assertMatch((String)$CDC1, "-->");
    }
}
