<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use TypeError;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedBadStringToken extends BadStringToken
{
    function __construct(String $delimiter, Array $pieces){
        if(isArraySequence($pieces) === FALSE){
            throw new TypeError("The given `\$pieces` is not an array sequence");
        }

        foreach($pieces as $offset => $piece){
            if(!$piece instanceof StringBitToken && !$piece instanceof EscapeToken){
                throw new TypeError(sprintf(
                    "\$pieces must be an array of `%s`",
                    StringBitToken::CLASS . "|" . EscapeToken::CLASS
                ));
            }
        }

        foreach($pieces as $offset => $piece){
            if($piece instanceof StringBitToken){
                $nextPiece = $pieces[$offset + 1] ?? NULL;
                if($nextPiece instanceof StringBitToken){
                    throw new InvalidToken(sprintf(
                        "Contiguous `%s` are disallowed",
                        StringBitToken::CLASS
                    ));
                }
            }
        }

        parent::__construct($delimiter, $pieces);
    }
}
