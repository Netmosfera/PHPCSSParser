<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValue;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\CurlySimpleBlockComponentValue;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;
use Netmosfera\PHPCSSAST\Tokens\Operators\SemicolonToken;
use function Netmosfera\PHPCSSAST\match;

class AtRuleNode implements RuleNode
{
    private $_token;

    private $_preludePieces;

    private $_terminator;

    /**
     * @param       AtKeywordToken $token
     * `AtKeywordToken`
     *
     * @param       ComponentValue[] $preludePieces
     * `Array<Int, ComponentValue>`
     *
     * @param       CurlySimpleBlockComponentValue|SemicolonToken|NULL $terminator
     * `CurlySimpleBlockComponentValue|SemicolonToken|NULL`
     * `NULL` is for EOF.
     */
    public function __construct(
        AtKeywordToken $token,
        array $preludePieces,
        $terminator
    ){
        assert(
            $terminator instanceof CurlySimpleBlockComponentValue ||
            $terminator instanceof SemicolonToken ||
            $terminator === NULL // EOF terminated
        );
        $this->_token = $token;
        $this->_preludePieces = $preludePieces;
        $this->_terminator = $terminator;
    }

    /** @inheritDoc */
    public function __toString(): String{ // @memo
        return
            (String)$this->_token .
            implode("", $this->_preludePieces) .
            $this->_terminator;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_token, $this->_token) &&
            match($other->_preludePieces, $this->_preludePieces) &&
            match($other->_terminator, $this->_terminator) &&
            TRUE;
    }
}
