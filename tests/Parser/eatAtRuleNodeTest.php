<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSAST\Parser\eatAtRuleNode;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if EOF
 * #2 | returns NULL if not an at-token
 * #3 | loop - unterminated
 * #4 | loop - ending with ;
 * #5 | loop - ending with { block
 */
class eatAtRuleNodeTest extends TestCase
{
    function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    function test1(Bool $testPrefix){
        $atRule = NULL;

        $stream = getTokenStream($testPrefix, "");
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyTokens($stream), "");
    }

    function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with an at-token"));
    }

    /** @dataProvider data2 */
    function test2(Bool $testPrefix, String $rest){
        $atRule = NULL;

        $stream = getTokenStream($testPrefix, $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyTokens($stream), $rest);
    }

    function data345(){
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new PreservedTokenNode(getToken("foo"));
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new PreservedTokenNode(getToken("+123%"));
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new SimpleBlockNode("(", [
            new PreservedTokenNode(getToken("+123%")),
            new PreservedTokenNode(getToken("+123%")),
        ], FALSE);
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new SimpleBlockNode("[", [
            new PreservedTokenNode(getToken("+123%")),
            new PreservedTokenNode(getToken("+123%")),
        ], FALSE);
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new PreservedTokenNode(getToken("bar"));
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new PreservedTokenNode(getToken("+456%"));
        $preludePieces[] = new PreservedTokenNode(getToken(" "));
        $preludePieces[] = new PreservedTokenNode(getToken("qux"));

        return cartesianProduct(
            [FALSE, TRUE],
            everySeqFromStart($preludePieces),
            ANY_CSS()
        );
    }

    /** @dataProvider data345 */
    function test3(Bool $testPrefix, array $preludePieces){
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, NULL);

        $stream = getTokenStream($testPrefix, $atRule . "");
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyTokens($stream), "");
    }

    /** @dataProvider data345 */
    function test4(Bool $testPrefix, array $preludePieces, String $rest){
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, ";");

        $stream = getTokenStream($testPrefix, $atRule . $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyTokens($stream), $rest);
    }

    /** @dataProvider data345 */
    function test5(Bool $testPrefix, array $preludePieces, String $rest){
        $blockComponents = [new PreservedTokenNode(getToken("foo"))];
        $block = new SimpleBlockNode("{", $blockComponents, FALSE);
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, $block);

        $stream = getTokenStream($testPrefix, $atRule . $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyTokens($stream), $rest);
    }
}
