<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

use function Netmosfera\PHPCSSAST\match;

class ListOfDeclarationsNode
{
    private $_list;

    private $_stringified;

    public function __construct(array $list){
        $this->_list = $list;
    }

    public function list(): array{
        return $this->_list;
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
}
