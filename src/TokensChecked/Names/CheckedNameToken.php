<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use TypeError;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedNameToken extends NameToken
{
    function __construct($pieces){
        $expectOffset = 0;
        foreach($pieces as $offset => $piece){
            if($offset !== $expectOffset++){
                throw new TypeError("array_values(\$pieces) === \$pieces is FALSE");
            }
            if(!$piece instanceof ValidEscapeToken && !$piece instanceof NameBitToken){
                throw new TypeError(sprintf(
                    "\$pieces must be an array of `%s`",
                    ValidEscapeToken::CLASS . "|" . NameBitToken::CLASS
                ));
            }
        }

        if(count($pieces) === 0){
            throw new InvalidToken("The token cannot be empty");
        }

        foreach($pieces as $offset => $piece){
            if($piece instanceof NameBitToken){
                $nextPiece = $pieces[$offset + 1] ?? NULL;
                if($nextPiece instanceof NameBitToken){
                    throw new InvalidToken(sprintf(
                        "Contiguous `%s` are disallowed",
                        NameBitToken::CLASS
                    ));
                }
            }
        }

        parent::__construct($pieces);
    }
}
