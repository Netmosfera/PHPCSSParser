<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests\Parser;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\DeclarationNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\InvalidDeclarationNode;
use Netmosfera\PHPCSSAST\Nodes\ListOfDeclarations;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use PHPUnit\Framework\TestCase;
use function Netmosfera\PHPCSSAST\Parser\Components\tokensToComponents;
use function Netmosfera\PHPCSSAST\Parser\eatListOfDeclarationsNode;
use function Netmosfera\PHPCSSASTTests\assertMatch;
use function Netmosfera\PHPCSSASTTests\cartesianProduct;
use function Netmosfera\PHPCSSASTTests\makePiecesSample;

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
                $data[] = new DeclarationNode(getTestToken("background"), [], [], [getTestToken("red")]);
                $data[] = new AtRuleNode(getTestToken("@foo"), [], new SemicolonToken());
                $data[] = new SemicolonToken();
                $data[] = new CommentToken("", FALSE);
                $data[] = new WhitespaceToken(" ");
                $data[] = new InvalidDeclarationNode(getTestTokens("+123"));
            }elseif($afterPiece instanceof WhitespaceToken){
                $data[] = new DeclarationNode(getTestToken("background"), [], [], [getTestToken("red")]);
                $data[] = new AtRuleNode(getTestToken("@foo"), [], new SemicolonToken());
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
        $list = new ListOfDeclarations($pieces);

        $nodes = tokensToComponents(getTestTokens(implode("", $pieces)));
        $actualList = eatListOfDeclarationsNode($nodes);

        assertMatch($actualList, $list);
    }
}
