<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDOTokenTest extends TestCase
{
    public function test1(){
        $CDO1 = new CDOToken();
        $CDO2 = new CDOToken();
        assertMatch($CDO1, $CDO2);
        assertMatch((String)$CDO1, "<!--");
    }
}
