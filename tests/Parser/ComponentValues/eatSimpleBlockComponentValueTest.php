<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ParenthesesSimpleBlockComponentValue;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\eatSimpleBlockComponentValue;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokenStream;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyTokenStreamRest;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a start-block delimiter
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatSimpleBlockComponentValueTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $block = NULL;

        $stream = getTestTokenStream($testPrefix, "");
        $actualBlock = eatSimpleBlockComponentValue($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data2(){
        return cartesianProduct(
            [FALSE, TRUE],
            ["=", ""],
            ANY_CSS("not starting with a start-block-delimiter")
        );
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $notDelimiter, String $rest){
        $block = NULL;

        $stream = getTestTokenStream($testPrefix, $notDelimiter . $rest);
        $actualBlock = eatSimpleBlockComponentValue($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), $notDelimiter . $rest);
    }

    public function data3(){
        $componentValues[] = getToken("foo");
        $componentValues[] = getToken("+123%");
        $componentValues[] = getToken("bar");
        $componentValues[] = getToken("+456%");
        $componentValues[] = getToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $componentValues){
        $block = new ParenthesesSimpleBlockComponentValue($componentValues, TRUE);

        $stream = getTestTokenStream($testPrefix, $block . "");
        $actualBlock = eatSimpleBlockComponentValue($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data4(){
        $componentValues[] = getToken("foo");
        $componentValues[] = getToken("+123%");
        $componentValues[] = getToken("bar");
        $componentValues[] = getToken("+456%");
        $componentValues[] = getToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, array $componentValues, String $rest){
        $block = new ParenthesesSimpleBlockComponentValue($componentValues, FALSE);

        $stream = getTestTokenStream($testPrefix, $block . $rest);
        $actualBlock = eatSimpleBlockComponentValue($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }
}
