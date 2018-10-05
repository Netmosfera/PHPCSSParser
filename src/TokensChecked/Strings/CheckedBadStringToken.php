<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use TypeError;

class CheckedBadStringToken extends BadStringToken
{
    public function __construct(String $delimiter, Array $pieces){
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
        }

        parent::__construct($delimiter, $pieces);
    }
}
