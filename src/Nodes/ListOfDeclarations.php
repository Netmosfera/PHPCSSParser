<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\Components\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\Components\DeclarationNode;
use Netmosfera\PHPCSSAST\Nodes\Components\InvalidDeclarationNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;

/**
 * @TODOC
 */
class ListOfDeclarations
{
    private $_list;

    /**
     * @param       DeclarationNode[]|AtRuleNode[]|InvalidDeclarationNode[]|SemicolonToken[]|WhitespaceToken[]|CommentToken[] $list
     * `Array<Int, DeclarationNode|AtRuleNode|InvalidDeclarationNode|SemicolonToken|WhitespaceToken|CommentToken>`
     * @TODOC
     */
    public function __construct(array $list){
        $this->_list = $list;
    }

    /**
     * @TODOC
     *
     * @return      DeclarationNode[]|AtRuleNode[]|InvalidDeclarationNode[]|SemicolonToken[]|WhitespaceToken[]|CommentToken[]
     * `Array<Int, DeclarationNode|AtRuleNode|InvalidDeclarationNode|SemicolonToken|WhitespaceToken|CommentToken>`
     * @TODOC
     */
    public function list(): array{
        return $this->_list;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return implode("", $this->_list);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_list, $this->_list);
    }
}
