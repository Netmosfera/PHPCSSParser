<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTTests\TokensChecked\Escapes;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use function Netmosfera\PHPCSSASTTests\assertMatch;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class EOFEscapeTokenTest extends TestCase
{
    function test1(){
        $EOFEscape1 = new EOFEscapeToken();
        $EOFEscape2 = new EOFEscapeToken();

        assertMatch($EOFEscape1, $EOFEscape2);

        assertMatch("\\", (String)$EOFEscape1);
        assertMatch((String)$EOFEscape1, (String)$EOFEscape2);

        assertMatch("", $EOFEscape1->getValue());
        assertMatch($EOFEscape1->getValue(), $EOFEscape2->getValue());
    }
}
