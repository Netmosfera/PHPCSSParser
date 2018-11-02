<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\AtRuleNode;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatAtRuleNode;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponent;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponents;
use function Netmosfera\PHPCSSASTTests\Parser\getTestComponentStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTestToken;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyComponentStreamRest;

/**
 * Tests in this file:
 *
 * #1 | returns NULL if not starting with at-token
 * #2 | loop EOF-truncated prelude
 * #3 | loop
 */
class eatAtRuleNodeTest extends TestCase
{
    public function data1(){
        return cartesianProduct([FALSE, TRUE], ANY_CSS("not starting with an at-token"));
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix, String $rest){
        $atRule = NULL;

        $stream = getTestComponentStream($testPrefix, $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }

    //------------------------------------------------------------------------------------

    public function data2(){
        $prelude = getTestComponents(" xx +42% /* xx */ > .xx [{not rule body}] * -1e42");
        return cartesianProduct([FALSE, TRUE], everySeqFromStart($prelude));
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, array $prelude){
        $atKeyword = getTestToken("@foo");
        $atRule = new AtRuleNode($atKeyword, $prelude, NULL);

        $stream = getTestComponentStream($testPrefix, $atRule . "");
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyComponentStreamRest($stream), "");
    }

    //------------------------------------------------------------------------------------

    public function data3(){
        $prelude = getTestComponents(" xx +42% /* xx */ > .xx [{not rule body}] * -1e42");
        return cartesianProduct(
            [FALSE, TRUE],
            everySeqFromStart($prelude),
            [getTestComponent(";"), getTestComponent("{ block }")],
            ANY_CSS()
        );
    }

    /** @dataProvider data3 */
    public function test3(Bool $testPrefix, array $prelude, $terminator, String $rest){
        $atKeyword = getTestToken("@foo");
        $atRule = new AtRuleNode($atKeyword, $prelude, $terminator);

        $stream = getTestComponentStream($testPrefix, $atRule . $rest);
        $actualAtRule = eatAtRuleNode($stream);

        assertMatch($actualAtRule, $atRule);
        assertMatch(stringifyComponentStreamRest($stream), $rest);
    }
}
