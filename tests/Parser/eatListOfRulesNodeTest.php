<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use PHPUnit\Framework\TestCase;
use Netmosfera\PHPCSSAST\Nodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Nodes\ListOfRulesNode;
use Netmosfera\PHPCSSAST\Nodes\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\PreservedTokenNode;
use Netmosfera\PHPCSSAST\Tokenizer\CheckedTokenizer;
use function Netmosfera\PHPCSSAST\Parser\eatListOfRulesNode;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\assertMatch;

/**
 * Tests in this file:
 *
 * @TODO
 */
class eatListOfRulesNodeTest extends TestCase
{
    public function data1(){
        $pieces[] = new PreservedTokenNode(getToken("\n"));
        $pieces[] = new PreservedTokenNode(getToken("/** comment! */"));

        // @TODO this should use parser functions e.g.
        // eatQualifiedRuleNode(getTokens("qualified > rule { }"));

        $pieces[] = new QualifiedRuleNode([
            new PreservedTokenNode(getToken("foo")),
            new PreservedTokenNode(getToken(" ")),
            new PreservedTokenNode(getToken(">")),
            new PreservedTokenNode(getToken(" ")),
            new PreservedTokenNode(getToken("bar")),
        ], new SimpleBlockNode("{", [
            new PreservedTokenNode(getToken("fooBar")),
            new PreservedTokenNode(getToken(" ")),
            new PreservedTokenNode(getToken("123.3deg")),
        ], FALSE));

        $pieces[] = new PreservedTokenNode(getToken("/** comment! */"));


        $pieces[] = new AtRuleNode(
            getToken("@at-keyword"),
            [
                new PreservedTokenNode(getToken(" ")),
                new PreservedTokenNode(getToken("foo")),
                new PreservedTokenNode(getToken(" ")),
                new PreservedTokenNode(getToken(">")),
                new PreservedTokenNode(getToken(" ")),
                new PreservedTokenNode(getToken("bar")),
            ],
            new SimpleBlockNode(
                "{",
                [
                    new PreservedTokenNode(getToken("fooBar")),
                    new PreservedTokenNode(getToken(" ")),
                    new PreservedTokenNode(getToken("123.3deg")),
                ],
                FALSE
            )
        );

        $pieces[] = new PreservedTokenNode(getToken("       "));

        return cartesianProduct(
            everySeqFromStart($pieces)
        );
    }

    /** @dataProvider data1 */
    public function test1(array $pieces){
        $rules = new ListOfRulesNode($pieces, TRUE);

        $tokenizer = new CheckedTokenizer();

        $tokens = $tokenizer->tokenize(implode("", $pieces));
        $actualRules = eatListOfRulesNode($tokens, TRUE);

        assertMatch($actualRules, $rules);
    }
}
