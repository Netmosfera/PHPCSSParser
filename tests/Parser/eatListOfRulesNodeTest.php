<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Nodes\Components\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ListOfRulesNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSAST\Parser\eatListOfRulesNode;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;

class CommentAfterInvalidRuleNode extends CommentToken{}
class WhitespaceAfterInvalidRuleNode extends WhitespaceToken{}
/**
 * Tests in this file:
 *
 * @TODO
 */
class eatListOfRulesNodeTest extends TestCase
{
    public function fofo(){
        return function($afterPiece, Bool $isLast){
            $data = [];

            if(
                $afterPiece instanceof InvalidRuleNode ||
                $afterPiece instanceof CommentAfterInvalidRuleNode
            ){
                $data[] = new CommentAfterInvalidRuleNode(" comment after invalid rule ", FALSE);

                $data[] = new WhitespaceAfterInvalidRuleNode("    ");
            }elseif($afterPiece instanceof WhitespaceAfterInvalidRuleNode ){
                $data[] = new CommentAfterInvalidRuleNode(" comment after invalid rule ", FALSE);
            }else{
                $data[] = new AtRuleNode(getToken("@at-rule-block"), [], new CurlySimpleBlockComponentValue([], FALSE));

                $data[] = new AtRuleNode(getToken("@at-rule-semicolon"), [], new SemicolonToken());

                $prelude = tokensToNodes(getTokens("valid > .rule > @not-at-rule"));
                $block = tokensToNodes(getTokens("{ color : purple ; }"))[0];
                $data[] = new QualifiedRuleNode($prelude, $block);

                $data[] = new CommentToken(" comment1 ", FALSE);

                if(!$afterPiece instanceof WhitespaceToken){
                    $data[] = new WhitespaceToken("\t\t");
                }

                $data[] = new InvalidRuleNode(tokensToNodes(getTokens("invalid * rule")));
            }

            return $data;
        };
    }

    public function data1(){
        return cartesianProduct(
            makePiecesSample($this->fofo(), TRUE, 5)
        );
    }

    /** @dataProvider data1 */
    public function test1(array $pieces){
        $list = new ListOfRulesNode($pieces, TRUE);

        $stream = getNodeStream(FALSE, implode("", $pieces));
        $actualList = eatListOfRulesNode($stream, TRUE);

        assertMatch($actualList, $list);
    }
}
