<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCDOToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDOTokenTest extends TestCase
{
    public function test1(){
        $CDO1 = new CheckedCDOToken();
        $CDO2 = new CheckedCDOToken();

        assertMatch($CDO1, $CDO2);

        assertMatch("<!--", (String)$CDO1);
        assertMatch((String)$CDO1, (String)$CDO2);
    }
}
