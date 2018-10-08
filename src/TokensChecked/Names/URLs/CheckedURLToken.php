<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

use function Netmosfera\PHPCSSAST\isArraySequence;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLBitToken;
use Netmosfera\PHPCSSAST\Tokens\Names\IdentifierToken;
use Netmosfera\PHPCSSAST\Tokens\Misc\WhitespaceToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;

class CheckedURLToken extends URLToken
{
    /** @inheritDoc */
    public function __construct(
        IdentifierToken $identifier,
        ?WhitespaceToken $whitespaceBefore,
        array $pieces,
        ?WhitespaceToken $whitespaceAfter,
        Bool $EOFTerminated
    ){
        assert(isArraySequence($pieces));

        if(strtolower($identifier->name()->intendedValue()) !== "url"){
            throw new InvalidToken("Identifier's lowercased intended value must match `url`");
        }

        foreach($pieces as $offset => $piece){
            assert($piece instanceof URLBitToken || $piece instanceof ValidEscapeToken);

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

        parent::__construct(
            $identifier,
            $whitespaceBefore,
            $pieces,
            $whitespaceAfter,
            $EOFTerminated
        );
    }
}
