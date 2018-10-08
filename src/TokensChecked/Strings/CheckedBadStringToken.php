<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Strings;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ContinuationEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

class CheckedBadStringToken extends BadStringToken
{
    /** @inheritDoc */
    public function __construct(String $delimiter, array $pieces){
        assert(isArraySequence($pieces));

        foreach($pieces as $offset => $piece){
            assert(
                $piece instanceof StringBitToken ||
                $piece instanceof ValidEscapeToken ||
                $piece instanceof ContinuationEscapeToken
            );

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
