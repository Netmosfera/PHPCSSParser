<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use Netmosfera\PHPCSSAST\Nodes\MainNodes\AtRuleNode;
use Netmosfera\PHPCSSAST\Nodes\MainNodes\QualifiedRuleNode;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDCToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CDOToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\CommentToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * @TODOC
 */
class ListOfRules
{
    private $_list;

    private $_isTopLevel;

    /**
     * @param       QualifiedRuleNode[]|AtRuleNode[]|WhitespaceToken[]|CommentToken[]|CDCToken[]|CDOToken[] $list
     * `Array<Int, QualifiedRuleNode|AtRuleNode|WhitespaceToken|CommentToken|CDCToken|CDOToken>
     * @TODOC
     *
     * @param       Bool $isTopLevel
     * `Bool`
     * If `TRUE` then the list may contain {@see CDOToken}s and {@see CDCToken}s.
     */
    public function __construct(array $list, Bool $isTopLevel){
        $this->_list = $list;
        $this->_isTopLevel = $isTopLevel;
    }

    /**
     * @TODOC
     *
     * @return      QualifiedRuleNode[]|AtRuleNode[]|WhitespaceToken[]|CommentToken[]|CDCToken[]|CDOToken[]
     * `Array<Int, QualifiedRuleNode|AtRuleNode|WhitespaceToken|CommentToken|CDCToken|CDOToken>
     * @TODOC
     */
    public function list(): array{
        return $this->_list;
    }

    /**
     * If `TRUE` then the list may contain {@see CDOToken}s and {@see CDCToken}s.
     *
     * @return      Bool
     * `Bool`
     * @TODOC
     */
    public function isTopLevel(): Bool{
        return $this->_isTopLevel;
    }

    /** @inheritDoc */
    public function __toString(): String{
        return implode("", $this->_list);
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_list, $this->_list);
    }
}
