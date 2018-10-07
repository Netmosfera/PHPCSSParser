<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\NameToken;

class CheckedNameToken extends NameToken
{
    public function __construct(array $pieces){
        assert(isArraySequence($pieces));

        if(count($pieces) === 0){
            throw new InvalidToken("The token cannot be empty");
        }

        foreach($pieces as $offset => $piece){
            assert($piece instanceof NameBitToken || $piece instanceof ValidEscapeToken);

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
