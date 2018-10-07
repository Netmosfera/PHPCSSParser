<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;

class CheckedIdentifierToken extends IdentifierToken
{
    public function __construct(NameToken $name){
        $pieces = $name->pieces();
        $firstPiece = $pieces[0];
        $stringifiedFirstPiece = (String)$firstPiece;

        // It's a valid identifier when starts with an escape, otherwise:
        if($firstPiece instanceof NameBitToken){
            if($stringifiedFirstPiece === "-"){
                if(($pieces[1] ?? NULL) === NULL){
                    // It's valid if "-" is followed by an escape
                    throw new InvalidToken();
                }
            }elseif(
                preg_match(
                    '/^(?:--|-?[' . SpecData::NAME_STARTERS_REGEX_SET . '])/usD',
                    $stringifiedFirstPiece
                ) === 0
            ){
                // It's valid if starts with:
                // - "--", optionally followed by zero or more name-code-points
                // - "-" is followed by one name-start-code-point,
                //   optionally followed by zero or more name-code-points
                // - starts with one name-start-code-point,
                //   optionally followed by zero or more name-code-points
                throw new InvalidToken();
            }
        }

        parent::__construct($name);
    }
}
