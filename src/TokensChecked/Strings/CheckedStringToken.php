<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use TypeError;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedStringToken extends StringToken
{
    function __construct(String $delimiter, $pieces, Bool $terminatedWithEOF){
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

        if(count($pieces) === 0){
            throw new InvalidToken("The token cannot be empty");
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

        if(
            end($pieces) instanceof EOFEscapeToken &&
            $terminatedWithEOF === FALSE
        ){
            throw new InvalidToken(sprintf(
                "A string that ends with a `%s` must be `\$terminatedWithEof`",
                EOFEscapeToken::CLASS
            ));
        }

        parent::__construct($delimiter, $pieces, $terminatedWithEOF);
    }
}
