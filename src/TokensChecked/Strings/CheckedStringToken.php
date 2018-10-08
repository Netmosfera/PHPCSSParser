<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;

class CheckedStringToken extends StringToken
{
    /** @inheritDoc */
    public function __construct(
        String $delimiter,
        array $pieces,
        Bool $EOFTerminated
    ){
        assert(isArraySequence($pieces));

        foreach($pieces as $offset => $piece){
            assert($piece instanceof StringBitToken || $piece instanceof EscapeToken);

            if($piece instanceof StringBitToken){
                $nextPiece = $pieces[$offset + 1] ?? NULL;
                if($nextPiece instanceof StringBitToken){
                    throw new InvalidToken(sprintf(
                        "Contiguous `%s` are disallowed",
                        StringBitToken::CLASS
                    ));
                }
            }

            $isLast = $offset === (count($pieces) - 1);
            if($piece instanceof EOFEscapeToken && $isLast === FALSE){
                throw new InvalidToken(sprintf(
                    "`%s` is only allowed as last element of `\$pieces`",
                    EOFEscapeToken::CLASS
                ));
            }
        }

        if(
            end($pieces) instanceof EOFEscapeToken &&
            $EOFTerminated === FALSE
        ){
            throw new InvalidToken(sprintf(
                "A string that ends with a `%s` must be `\$EOFTerminated`",
                EOFEscapeToken::CLASS
            ));
        }

        parent::__construct($delimiter, $pieces, $EOFTerminated);
    }
}
