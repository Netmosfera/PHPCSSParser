<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\Algorithms;

use Netmosfera\PHPCSSAST\Nodes\Components\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Algorithms\eatQualifiedRuleNode;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToComponentValues;
use function Netmosfera\PHPCSSASTDev\Examples\ANY_CSS;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\Parser\everySeqFromStart;
use function Netmosfera\PHPCSSASTTests\Parser\getTestNodeStream;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;
use function Netmosfera\PHPCSSASTTests\Parser\stringifyNodeStreamRest;

// @TODO make tests dynamic with data providers

/**
 * Tests in this file:
 *
 * #1 | returns NotAQualifiedRule if only has prelude (ie ends with eof)
 * #2 | loop
 */
class eatQualifiedRuleNodeTest extends TestCase
{
    public function data1(){
        $rest[] = "";
        $rest[] = "    ";
        $rest[] = "/* outside */";
        $rest[] = "    /* outside */   /* also outside */   ";
        $rest[] = "/* outside */      /* also outside */";
        return cartesianProduct([FALSE, TRUE], $rest);
    }

    /** @dataProvider data1 */
    public function test1(Bool $testPrefix, String $rest){
        $componentValues = tokensToComponentValues(getTokens("foo +123% /* comment */ foo"));
        $invalidRule = new InvalidRuleNode($componentValues);

        $stream = getTestNodeStream($testPrefix, implode("", $componentValues) . $rest);
        $actualInvalidRule = eatQualifiedRuleNode($stream);

        assertMatch($actualInvalidRule, $invalidRule);
        assertMatch(stringifyNodeStreamRest($stream), $rest);
    }

    public function data2(){
        $preludePieces = tokensToComponentValues(getTokens(
            "foo +123% /* comment */ > .bar [{this is not the block}] * bar -1e-45"
        ));
        return cartesianProduct(
            [FALSE, TRUE],
            everySeqFromStart($preludePieces),
            ANY_CSS()
        );
    }

    /** @dataProvider data2 */
    public function test2(Bool $testPrefix, array $preludePieces, String $rest){
        $block = new CurlySimpleBlockComponentValue([], FALSE);
        $qualifiedRule = new QualifiedRuleNode($preludePieces, $block);

        $stream = getTestNodeStream($testPrefix, $qualifiedRule . $rest);
        $actualQualifiedRule = eatQualifiedRuleNode($stream);

        assertMatch($actualQualifiedRule, $qualifiedRule);
        assertMatch(stringifyNodeStreamRest($stream), $rest);
    }
}
