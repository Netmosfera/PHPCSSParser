<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use TypeError;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CheckedBadURLToken extends BadURLToken
{
    function __construct(
        ?WhitespaceToken $whitespaceBefore,
        Array $pieces,
        BadURLRemnantsToken $badURLRemnants
    ){
        if(isArraySequence($pieces) === FALSE){
            throw new TypeError("The given `\$pieces` is not an array sequence");
        }

        foreach($pieces as $offset => $piece){
            if(!$piece instanceof URLBitToken && !$piece instanceof ValidEscapeToken){
                throw new TypeError(sprintf(
                    "\$pieces must be an array of `%s`",
                    URLBitToken::CLASS . "|" . ValidEscapeToken::CLASS
                ));
            }
        }

        foreach($pieces as $offset => $piece){
            if($piece instanceof URLBitToken){
                $nextPiece = $pieces[$offset + 1] ?? NULL;
                if($nextPiece instanceof URLBitToken){
                    throw new InvalidToken(sprintf(
                        "Contiguous `%s` are disallowed",
                        URLBitToken::CLASS
                    ));
                }
            }
        }

        parent::__construct($whitespaceBefore, $pieces, $badURLRemnants);
    }
}