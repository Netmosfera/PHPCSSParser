<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockNode;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatQualifiedRuleNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * #1 | returns NotAQualifiedRule if only has prelude (ie ends with eof)
 * #2 | loop
 */
class eatQualifiedRuleNodeTest extends TestCase
{
    public function test1(){
        $a = tokensToNodes(getTokens("foo +123% /* comment */ foo"));
        $rest = "   /* outside */   /* also outside */   ";
        $invalidRule = new InvalidRuleNode($a->nodes());

        $stream = getNodeStream(FALSE, $a . $rest);
        $actualInvalidRule = eatQualifiedRuleNode($stream);

        assertMatch($actualInvalidRule, $invalidRule);
        assertMatch(stringifyNodes($stream), $rest);
    }

    public function data2(){
        $preludePieces = tokensToNodes(getTokens(
            "foo +123% /* comment */ > .bar [{this is not the block}] * bar -1e-45"
        ))->nodes();
        return cartesianProduct(
            [FALSE, TRUE],
            everySeqFromStart($preludePieces),
            ANY_CSS()
        );
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, array $preludePieces, String $rest){
        $block = new SimpleBlockNode("{", [], FALSE);
        $qualifiedRule = new QualifiedRuleNode($preludePieces, $block);

        $stream = getNodeStream($testPrefix, $qualifiedRule . $rest);
        $actualQualifiedRule = eatQualifiedRuleNode($stream);

        assertMatch($actualQualifiedRule, $qualifiedRule);
        assertMatch(stringifyNodes($stream), $rest);
    }
}
