<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Misc\CheckedCDCToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class CDCTokenTest extends TestCase
{
    function test1(){
        $CDC1 = new CheckedCDCToken();
        $CDC2 = new CheckedCDCToken();

        assertMatch($CDC1, $CDC2);

        assertMatch("-->", (String)$CDC1);
        assertMatch((String)$CDC1, (String)$CDC2);
    }
}
