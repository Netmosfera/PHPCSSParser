<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use function Netmosfera\PHPCSSAST\Parser\eatSimpleBlockNode;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not a start-block delimiter
 * #3 | loop unterminated
 * #4 | loop terminated
 */
class eatSimpleBlockNodeTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $block = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualBlock = eatSimpleBlockNode($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokens($stream), "");
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

        $stream = getTokenStream($testPrefix, $notDelimiter . $rest);
        $actualBlock = eatSimpleBlockNode($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokens($stream), $notDelimiter . $rest);
    }

    public function data3(){
        $componentValues[] = new PreservedTokenNode(getToken("foo"));
        $componentValues[] = new PreservedTokenNode(getToken("+123%"));
        $componentValues[] = new PreservedTokenNode(getToken("bar"));
        $componentValues[] = new PreservedTokenNode(getToken("+456%"));
        $componentValues[] = new PreservedTokenNode(getToken("qux"));
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues));
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $componentValues){
        $block = new SimpleBlockNode("(", $componentValues, TRUE);

        $stream = getTokenStream($testPrefix, $block . "");
        $actualBlock = eatSimpleBlockNode($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokens($stream), "");
    }

    public function data4(){
        $componentValues[] = new PreservedTokenNode(getToken("foo"));
        $componentValues[] = new PreservedTokenNode(getToken("+123%"));
        $componentValues[] = new PreservedTokenNode(getToken("bar"));
        $componentValues[] = new PreservedTokenNode(getToken("+456%"));
        $componentValues[] = new PreservedTokenNode(getToken("qux"));
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($componentValues), ANY_CSS());
    }

    /** @dataProvider data4 */
    public function test4(Bool $testPrefix, array $componentValues, String $rest){
        $block = new SimpleBlockNode("(", $componentValues, FALSE);

        $stream = getTokenStream($testPrefix, $block . $rest);
        $actualBlock = eatSimpleBlockNode($stream);

        assertMatch($actualBlock, $block);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
