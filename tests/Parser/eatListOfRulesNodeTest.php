<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\InvalidRuleNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Nodes\ListOfRules;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
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
                $data[] = new AtRuleNode(getTestToken("@at-rule-block"), [], new CurlySimpleBlockComponent([], FALSE));

                $data[] = new AtRuleNode(getTestToken("@at-rule-semicolon"), [], new SemicolonToken());

                $prelude = tokensToComponents(getTestTokens("valid > .rule > @not-at-rule"));
                $block = tokensToComponents(getTestTokens("{ color : purple ; }"))[0];
                $data[] = new QualifiedRuleNode($prelude, $block);

                $data[] = new CommentToken(" comment1 ", FALSE);

                if(!$afterPiece instanceof WhitespaceToken){
                    $data[] = new WhitespaceToken("\t\t");
                }

                $data[] = new InvalidRuleNode(tokensToComponents(getTestTokens("invalid * rule")));
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
        $list = new ListOfRules($pieces, TRUE);

        $stream = getTestComponentStream(FALSE, implode("", $pieces));
        $actualList = eatListOfRulesNode($stream, TRUE);

        assertMatch($actualList, $list);
    }
}
