<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\MainNodes;

use Netmosfera\PHPCSSAST\Nodes\Components\Component;
use Netmosfera\PHPCSSAST\Nodes\Components\CurlySimpleBlockComponent;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use function Netmosfera\PHPCSSAST\match;

/**
 * A {@see AtRuleNode} is an {@see AtKeywordToken} followed by the prelude (a list of
 * {@see Component}s), followed by a {@see CurlySimpleBlockComponent} or
 * a {@see SemiColonToken}.
 */
class AtRuleNode
{
    /**
     * @var         AtKeywordToken
     * `AtKeywordToken`
     * @TODOC
     */
    private $_token;

    /**
     * @var         Component[]
     * `Array<Int, Component>`
     * @TODOC
     */
    private $_prelude;

    /**
     * @var         CurlySimpleBlockComponent|SemicolonToken|NULL
     * `CurlySimpleBlockComponent|SemicolonToken|NULL`
     * @TODOC
     */
    private $_terminator;

    /**
     * @param       AtKeywordToken $token
     * `AtKeywordToken`
     * @TODOC
     *
     * @param       Component[] $prelude
     * `Array<Int, Component>`
     * @TODOC
     *
     * @param       CurlySimpleBlockComponent|SemicolonToken|NULL $terminator
     * `CurlySimpleBlockComponent|SemicolonToken|NULL`
     * A curly block, a semicolon, or `NULL` is for EOF.
     */
    public function __construct(
        AtKeywordToken $token,
        array $prelude,
        $terminator
    ){
        assert(
            $terminator instanceof CurlySimpleBlockComponent ||
            $terminator instanceof SemicolonToken ||
            $terminator === NULL
        );
        $this->_token = $token;
        $this->_prelude = $prelude;
        $this->_terminator = $terminator;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            (String)$this->_token .
            implode("", $this->_prelude) .
            $this->_terminator;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_token, $this->_token) &&
            match($other->_prelude, $this->_prelude) &&
            match($other->_terminator, $this->_terminator);
    }
}
