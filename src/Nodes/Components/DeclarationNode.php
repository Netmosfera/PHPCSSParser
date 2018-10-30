<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Nodes\Components;

use function Netmosfera\PHPCSSAST\match;
use Netmosfera\PHPCSSAST\Nodes\ComponentValues\ComponentValueNode;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

class DeclarationNode
{
    private $_identifier;

    private $_whitespaceBeforeColon;

    private $_whitespaceAfterColon;

    private $_definition;

    private $_stringValue;

    public function __construct(
        IdentifierToken $identifier,
        array $whitespaceBeforeColon,
        array $whitespaceAfterColon,
        array $definition
    ){
        $this->_identifier = $identifier;
        $this->_whitespaceBeforeColon = $whitespaceBeforeColon;
        $this->_whitespaceAfterColon = $whitespaceAfterColon;
        $this->_definition = $definition;

        // $whitespaceBeforeColon is a sequence of whitespace and comment tokens

        // $whitespaceAfterColon is a sequence of whitespace and comment tokens

        // $definition can be empty -- that also counts as a valid declaration
        // when definition is empty, whitespaceAfterDefinition is also empty
        foreach($definition as $definitionPiece){
            assert($definitionPiece instanceof ComponentValueNode);
        }

        // $whitespaceAfterDefinition is a sequence of whitespace and comment tokens
    }

    /** @inheritDoc */
    public function __toString(): String{
        if($this->_stringValue === NULL){
            $this->_stringValue .= $this->_identifier;
            $this->_stringValue .= implode("", $this->_whitespaceBeforeColon) . ":";
            $this->_stringValue .= implode("", $this->_whitespaceAfterColon);
            $this->_stringValue .= implode("", $this->_definition);
        }
        return $this->_stringValue;
    }

    /** @inheritDoc */
    public function equals($other): Bool{
        return
            $other instanceof self &&
            match($other->_identifier, $this->_identifier) &&
            match($other->_whitespaceBeforeColon, $this->_whitespaceBeforeColon) &&
            match($other->_whitespaceAfterColon, $this->_whitespaceAfterColon) &&
            match($other->_definition, $this->_definition) &&
            TRUE;
    }
}
