<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;
use TypeError;

class CheckedNameToken extends NameToken
{
    public function __construct(Array $pieces){
        if(isArraySequence($pieces) === FALSE){
            throw new TypeError(
                "The given `\$pieces` is not an array sequence"
            );
        }

        foreach($pieces as $offset => $piece){
            if(
                !$piece instanceof NameBitToken &&
                !$piece instanceof ValidEscapeToken
            ){
                throw new TypeError(sprintf(
                    "\$pieces must be an array of `%s`",
                    NameBitToken::CLASS . "|" . ValidEscapeToken::CLASS
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
