<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Nodes\QualifiedRuleNode;
use function Netmosfera\PHPCSSAST\Parser\eatQualifiedRuleNode;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #2 | loop - unterminated
 * #3 | loop - { block
 */
class eatQualifiedRuleNodeTest extends TestCase
{
    function data12(){
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

    /** @dataProvider data12 */
    function test1(Bool $testPrefix, array $preludePieces){
        $qualifiedRule = new QualifiedRuleNode($preludePieces, NULL);

        $stream = getTokenStream($testPrefix, $qualifiedRule . "");
        $actualQualifiedRule = eatQualifiedRuleNode($stream);

        assertMatch($actualQualifiedRule, $qualifiedRule);
        assertMatch(stringifyTokens($stream), "");
    }

    /** @dataProvider data12 */
    function test2(Bool $testPrefix, array $preludePieces){
        $blockComponents = [new PreservedTokenNode(getToken("foo"))];
        $block = new SimpleBlockNode("{", $blockComponents, FALSE);
        $qualifiedRule = new QualifiedRuleNode($preludePieces, $block);

        $stream = getTokenStream($testPrefix, $qualifiedRule . "");
        $actualQualifiedRule = eatQualifiedRuleNode($stream);

        assertMatch($actualQualifiedRule, $qualifiedRule);
        assertMatch(stringifyTokens($stream), "");
    }
}
