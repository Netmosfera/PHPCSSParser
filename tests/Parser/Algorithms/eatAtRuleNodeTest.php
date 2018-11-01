<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\Components\AtRuleNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatAtRuleNode;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyNodes;
use function Netmosfera\PHPCSSASTTests\Parser\getNodeStream;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
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
    public function data1(){
        return cartesianProduct([FALSE, TRUE]);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix){
        $atRule = NULL;

        $stream = getNodeStream($testPrefix, "");
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyNodes($stream), "");
    }

    public function data2(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with an at-token"));
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, String $rest){
        $atRule = NULL;

        $stream = getNodeStream($testPrefix, $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyNodes($stream), $rest);
    }

    public function data345(){
        $preludePieces = tokensToNodes(getTokens(
            " foo +123% /* comment */ > .bar [{this is not the block}] * bar -1e-45"
        ));
        return cartesianProduct(
            [FALSE, TRUE],
            everySeqFromStart($preludePieces),
            ANY_CSS()
        );
    }

    /** @dataProvider data345 */
    public function test3(Bool $testPrefix, array $preludePieces){
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, NULL);

        $stream = getNodeStream($testPrefix, $atRule . "");
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyNodes($stream), "");
    }

    /** @dataProvider data345 */
    public function test4(Bool $testPrefix, array $preludePieces, String $rest){
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, new SemicolonToken());

        $stream = getNodeStream($testPrefix, $atRule . $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyNodes($stream), $rest);
    }

    /** @dataProvider data345 */
    public function test5(Bool $testPrefix, array $preludePieces, String $rest){
        $block = tokensToNodes(getTokens("{test block}"))[0];
        $atRule = new AtRuleNode(getToken("@foo"), $preludePieces, $block);

        $stream = getNodeStream($testPrefix, $atRule . $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyNodes($stream), $rest);
    }
}
