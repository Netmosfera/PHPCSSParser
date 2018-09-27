<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedIdentifierToken extends IdentifierToken
{
    function __construct(NameToken $name){
        $pieces = $name->getPieces();
        $firstPiece = $pieces[0];
        $stringifiedFirstPiece = (String)$firstPiece;

        // It's a valid identifier when starts with an escape, otherwise:
        if($firstPiece instanceof NameBitToken){
            if($stringifiedFirstPiece === "-"){
                if(($pieces[1] ?? NULL) === NULL){
                    // If "-" is followed by an escape
                    throw new InvalidToken();
                }
            }elseif(preg_match('/^(?:--|-?[' . SpecData::NAME_STARTERS_SET . '])/usD', $stringifiedFirstPiece) === 0){
                // If "--" optionally followed by zero or more name-code-points
                // If "-" followed by one name-start-code-point, optionally followed by zero or more name-code-points
                // If one name-start-code-point, optionally followed by zero or more name-code-points
                throw new InvalidToken();
            }
        }

        parent::__construct($name);
    }
}
