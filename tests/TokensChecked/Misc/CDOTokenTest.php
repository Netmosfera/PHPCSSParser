<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDOTokenTest extends TestCase
{
    function test1(){
        $CDO1 = new CDOToken();
        $CDO2 = new CDOToken();

        assertMatch($CDO1, $CDO2);

        assertMatch("<!--", (String)$CDO1);
        assertMatch((String)$CDO1, (String)$CDO2);
    }
}
