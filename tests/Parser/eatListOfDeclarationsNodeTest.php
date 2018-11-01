<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser\ComponentValues;

use Netmosfera\PHPCSSAST\Nodes\Components\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\DeclarationNode;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidDeclarationNode;
use Netmosfera\PHPCSSAST\Nodes\ListOfDeclarationsNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\ComponentValues\tokensToNodes;
use function Netmosfera\PHPCSSAST\Parser\eatListOfDeclarationsNode;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;
use function Netmosfera\PHPCSSASTTests\Parser\getToken;
use function Netmosfera\PHPCSSASTTests\Parser\getTokens;

class CommentAfterDeclarationBeforeSemicolon extends CommentToken{}
class WhitespaceAfterDeclarationBeforeSemicolon extends WhitespaceToken{}

/**
 * Tests in this file:
 *
 * #1 |
 */
class eatListOfDeclarationsNodeTest extends TestCase
{
    public function fofo(){
        return function($afterPiece, Bool $isLast){
            $data = [];
            if($afterPiece instanceof CommentAfterDeclarationBeforeSemicolon){
                $data[] = new SemicolonToken();
                $data[] = new CommentAfterDeclarationBeforeSemicolon("bco", FALSE);
                $data[] = new WhitespaceAfterDeclarationBeforeSemicolon("\t\t");
            }elseif($afterPiece instanceof WhitespaceAfterDeclarationBeforeSemicolon){
                $data[] = new SemicolonToken();
                $data[] = new CommentAfterDeclarationBeforeSemicolon("bco", FALSE);
            }elseif(
                $afterPiece === NULL ||
                $afterPiece instanceof AtRuleNode ||
                $afterPiece instanceof SemicolonToken ||
                $afterPiece instanceof CommentToken
            ){
                $data[] = new DeclarationNode(getToken("background"), [], [], [getToken("red")]);
                $data[] = new AtRuleNode(getToken("@foo"), [], new SemicolonToken());
                $data[] = new SemicolonToken();
                $data[] = new CommentToken("", FALSE);
                $data[] = new WhitespaceToken(" ");
                $data[] = new InvalidDeclarationNode(getTokens("+123"));
            }elseif($afterPiece instanceof WhitespaceToken){
                $data[] = new DeclarationNode(getToken("background"), [], [], [getToken("red")]);
                $data[] = new AtRuleNode(getToken("@foo"), [], new SemicolonToken());
                $data[] = new SemicolonToken();
                $data[] = new CommentToken("", FALSE);
            }elseif(
                $afterPiece instanceof DeclarationNode ||
                $afterPiece instanceof InvalidDeclarationNode
            ){
                $data[] = new SemicolonToken();
                $data[] = new CommentAfterDeclarationBeforeSemicolon("bco", FALSE);
                $data[] = new WhitespaceAfterDeclarationBeforeSemicolon("\t\t");
            }else{
                throw new \Error(get_class($afterPiece));
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
        $list = new ListOfDeclarationsNode($pieces);

        $nodes = tokensToNodes(getTokens(implode("", $pieces)));
        $actualList = eatListOfDeclarationsNode($nodes);

        assertMatch($actualList, $list);
    }
}
