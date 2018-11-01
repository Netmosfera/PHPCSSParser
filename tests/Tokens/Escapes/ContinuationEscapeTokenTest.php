<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Tokens\Escapes;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use function Netmosfera\PHPCSSASTDev\Data\CodePointSeqsSets\getNewlineSeqsSet;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | test getters
 */
class ContinuationEscapeTokenTest extends TestCase
{
    public function data1(){
        return cartesianProduct(getNewlineSeqsSet());
    }

    /** @dataProvider data1 */
    public function test1(String $newline){
        $escape1 = new ContinuationEscapeToken($newline);
        $escape2 = new ContinuationEscapeToken($newline);
        assertMatch($escape1, $escape2);
        assertMatch((String)$escape1, "\\" . $newline);
        assertMatch($escape1->codePoint(), $newline);
        assertMatch($escape1->intendedValue(), "");
    }
}
