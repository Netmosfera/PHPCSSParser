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
        $EOFEscape1 = new CheckedEOFEscapeToken();
        $EOFEscape2 = new CheckedEOFEscapeToken();

        assertMatch($EOFEscape1, $EOFEscape2);

        assertMatch("\\", (String)$EOFEscape1);
        assertMatch((String)$EOFEscape1, (String)$EOFEscape2);

        assertMatch("", $EOFEscape1->intendedValue());
        assertMatch($EOFEscape1->intendedValue(), $EOFEscape2->intendedValue());
    }
}
