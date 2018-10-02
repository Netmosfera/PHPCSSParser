<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\StandardTokenizer;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedEOFEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\Escapes\CheckedContinuationEscapeToken;
use function Netmosfera\PHPCSSAST\StandardTokenizer\eatNullEscapeToken;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_UTF8;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #0 | not starting with \
 * #1 | EOF
 * #2 | newline (continuation)
 * #3 | valid escape
 */
class eatNullEscapeTokenTest extends TestCase
{
    public function data0(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8("not starting with \\"));
    }

    /** @dataProvider data0 */
    public function test0(String $prefix, String $rest){
        $escape = NULL;

        $traverser = getTraverser($prefix, $rest);
        $actualEscape = eatNullEscapeToken($traverser, "\f");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data1(){
        return cartesianProduct(ANY_UTF8());
    }

    /** @dataProvider data1 */
    public function test1(String $prefix){
        $escape = new CheckedEOFEscapeToken();

        $traverser = getTraverser($prefix, "\\");
        $actualEscape = eatNullEscapeToken($traverser, "\f");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), "");
    }

    public function data2(){
        return cartesianProduct(ANY_UTF8(), ANY_UTF8());
    }

    /** @dataProvider data2 */
    public function test2(String $prefix, String $rest){
        $escape = new CheckedContinuationEscapeToken("\f");

        $traverser = getTraverser($prefix, "\\\f" . $rest);
        $actualEscape = eatNullEscapeToken($traverser, "\f");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), $rest);
    }

    public function data3(){
        return cartesianProduct(
            ANY_UTF8(),
            ["f", "FFAACC", "FAC", "fAc", "x", "a", "z", "@"],
            ANY_UTF8()
        );
    }

    /** @dataProvider data3 */
    public function test3(String $prefix, String $validEscape, String $rest){
        $escape = NULL;

        $traverser = getTraverser($prefix, "\\" . $validEscape . $rest);
        $actualEscape = eatNullEscapeToken($traverser, "\f");

        assertMatch($actualEscape, $escape);
        assertMatch($traverser->eatAll(), "\\" . $validEscape . $rest);
    }
}
