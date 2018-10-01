<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class EOFEscapeTokenTest extends TestCase
{
    public function test1(){
        $escape1 = new CheckedEOFEscapeToken();
        $escape2 = new CheckedEOFEscapeToken();
        assertMatch($escape1, $escape2);
        assertMatch((String)$escape1, "\\");
        assertMatch($escape1->intendedValue(), "");
    }
}
