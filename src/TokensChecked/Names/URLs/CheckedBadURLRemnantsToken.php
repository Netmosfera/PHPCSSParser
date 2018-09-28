<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use TypeError;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedBadURLRemnantsToken extends BadURLRemnantsToken
{
    function __construct(Array $pieces, Bool $terminatedWithEOF){
        if(isArraySequence($pieces) === FALSE){
            throw new TypeError("The given `\$pieces` is not an array sequence");
        }

        foreach($pieces as $offset => $piece){
            if(!$piece instanceof BadURLRemnantsBitToken && !$piece instanceof EscapeToken){
                throw new TypeError(sprintf(
                    "\$pieces must be an array of `%s`",
                    BadURLRemnantsBitToken::CLASS . "|" . EscapeToken::CLASS
                ));
            }
        }

        if(count($pieces) === 0){
            throw new InvalidToken("The token cannot be empty");
        }

        foreach($pieces as $offset => $piece){
            if($piece instanceof BadURLRemnantsBitToken){
                $nextPiece = $pieces[$offset + 1] ?? NULL;
                if($nextPiece instanceof BadURLRemnantsBitToken){
                    throw new InvalidToken(sprintf(
                        "Contiguous `%s` are disallowed",
                        BadURLRemnantsBitToken::CLASS
                    ));
                }
            }
        }

        parent::__construct($pieces, $terminatedWithEOF);
    }
}
