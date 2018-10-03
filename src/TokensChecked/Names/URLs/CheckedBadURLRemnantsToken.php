<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\TokensChecked\Names\URLs;

use TypeError;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\URLToken;
use Netmosfera\PHPCSSAST\TokensChecked\InvalidToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\EOFEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Escapes\ValidEscapeToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsToken;
use Netmosfera\PHPCSSAST\Tokens\Names\URLs\BadURLRemnantsBitToken;
use function Netmosfera\PHPCSSAST\isArraySequence;
use function preg_match;

class CheckedBadURLRemnantsToken extends BadURLRemnantsToken
{
    public function __construct(Array $pieces, Bool $precedesEOF){
        if(isArraySequence($pieces) === FALSE){
            throw new TypeError(
                "The given `\$pieces` is not an array sequence"
            );
        }

        foreach($pieces as $offset => $piece){
            if(
                !$piece instanceof BadURLRemnantsBitToken &&
                !$piece instanceof EscapeToken
            ){
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

        if(
            end($pieces) instanceof EOFEscapeToken &&
            $precedesEOF === FALSE
        ){
            throw new InvalidToken(sprintf(
                "A URLToken that ends with a `%s` must be `\$precedesEOF`",
                EOFEscapeToken::CLASS
            ));
        }

        $firstPiece = $pieces[0];
        if((
            $firstPiece instanceof BadURLRemnantsBitToken &&
            preg_match(
                '/^[' . SpecData::URL_TOKEN_BIT_CPS_REGEX_SET . ']/usD',
                (String)$firstPiece
            ) === 1
        ) || (
            $firstPiece instanceof ValidEscapeToken
        )){
            throw new InvalidToken(sprintf(
                "`%s` must begin with a code point not allowed in a `%s` " .
                "or must begin with an invalid escape",
                BadURLRemnantsToken::CLASS,
                URLToken::CLASS
            ));
        }

        parent::__construct($pieces, $precedesEOF);
    }
}
