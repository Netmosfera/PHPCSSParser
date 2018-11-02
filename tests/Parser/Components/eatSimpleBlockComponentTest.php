<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Components;

use Netmosfera\PHPCSSAST\Nodes\Components\ParenthesesSimpleBlockComponent;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\eatSimpleBlockComponent;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\getTestTokenStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyTokenStreamRest;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a start-block delimiter
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatSimpleBlockComponentTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $block = NULL;

        $stream = getTestTokenStream($testPrefix, "");
        $actualBlock = eatSimpleBlockComponent($stream);

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
        $actualBlock = eatSimpleBlockComponent($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), $notDelimiter . $rest);
    }

    public function data3(){
        $componentValues[] = getTestToken("foo");
        $componentValues[] = getTestToken("+123%");
        $componentValues[] = getTestToken("bar");
        $componentValues[] = getTestToken("+456%");
        $componentValues[] = getTestToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $componentValues){
        $block = new ParenthesesSimpleBlockComponent($componentValues, TRUE);

        $stream = getTestTokenStream($testPrefix, $block . "");
        $actualBlock = eatSimpleBlockComponent($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), "");
    }

    public function data4(){
        $componentValues[] = getTestToken("foo");
        $componentValues[] = getTestToken("+123%");
        $componentValues[] = getTestToken("bar");
        $componentValues[] = getTestToken("+456%");
        $componentValues[] = getTestToken("qux");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, array $componentValues, String $rest){
        $block = new ParenthesesSimpleBlockComponent($componentValues, FALSE);

        $stream = getTestTokenStream($testPrefix, $block . $rest);
        $actualBlock = eatSimpleBlockComponent($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokenStreamRest($stream), $rest);
    }
}
