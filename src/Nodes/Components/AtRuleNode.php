<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\SimpleBlockNode;
use Netmosfera\PHPCSSAST\Tokens\Names\AtKeywordToken;

class AtRuleNode implements RuleNode
{
    private $_token;

    private $_preludePieces;

    private $_terminator;

    private $_stringified;

    public function __construct(
        AtKeywordToken $token,
        array $preludePieces,
        $terminator
    ){
        assert(
            $terminator instanceof SimpleBlockNode ||
            $terminator === ";" ||
            $terminator === NULL     // EOF terminated
        );
        $this->_token = $token;
        // @TODO prelude pieces must not start with an identifier or anything
        // that would be part of the at-keyword-token
        $this->_preludePieces = $preludePieces;
        $this->_terminator = $terminator;
    }


    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified = (String)$this->_token;
            $this->_stringified .= implode("", $this->_preludePieces);
            $this->_stringified .= $this->_terminator;
        }
        return $this->_stringified;
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
