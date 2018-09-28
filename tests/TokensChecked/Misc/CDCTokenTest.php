<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Misc;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
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
        $CDC1 = new CDCToken();
        $CDC2 = new CDCToken();

        assertMatch($CDC1, $CDC2);

        assertMatch("-->", (String)$CDC1);
        assertMatch((String)$CDC1, (String)$CDC2);
    }
}
