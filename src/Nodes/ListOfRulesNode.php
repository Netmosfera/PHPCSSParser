<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use function Netmosfera\PHPCSSAST\match;

class ListOfRulesNode
{
    private $_list;

    private $_isTopLevel;

    private $_stringified;

    public function __construct(array $list, Bool $isTopLevel){
        $this->_list = $list;
        $this->_isTopLevel = $isTopLevel;
    }

    public function list(): array{
        return $this->_list;
    }

    public function isTopLevel(): Bool{
        return $this->_isTopLevel;
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringified === NULL){
            $this->_stringified = implode("", $this->_list);
        }
        return $this->_stringified;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_list, $this->_list) &&
            TRUE;
    }

    // @TODO this must have a method to get all rules exclusive of whitespace tokens and comments
    // also the initial @charset rule must be removed, but only if top level

    // cdc and cdo tokens also appear in the list if top level - otherwise, they are part of a qualifiedrule's prelude
}
