<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes;

class ListOfRulesNode
{
    private $_list;

    public function __construct(array $list){
        $this->_list = $list;
    }

    public function list(): array{
        return $this->_list;
    }

    // @TODO this must have a method to get all rules exclusive of whitespace tokens and comments
    // also the initial @charset rule must be removed, but only if top level

    // cdc and cdo tokens also appear in the list if top level - otherwise, they are part of a qualifiedrule's prelude
}
