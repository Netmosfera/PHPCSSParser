<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class EOFEscapeTokenTest extends TestCase
{
    public function test1(){
        $escape1 = new EOFEscapeToken();
        $escape2 = new EOFEscapeToken();
        assertMatch($escape1, $escape2);
        assertMatch((String)$escape1, "\\");
        assertMatch($escape1->intendedValue(), "");
    }
}
