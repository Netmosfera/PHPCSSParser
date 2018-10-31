<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;

function eatStringToken(
    Traverser $traverser,
    String $newlineRegexSet,
    Closure $eatEscapeToken
): ?AnyStringToken{
    $delimiter = $traverser->eatPattern('\'|"');

    if(isset($delimiter));else{
        return NULL;
    }

    $pieces = [];

    for(;;){
        if(isset($traverser->data[$traverser->index]));else{
            return new StringToken($delimiter, $pieces, TRUE);
        }

        if($traverser->eatString($delimiter) === $delimiter){
            return new StringToken($delimiter, $pieces, FALSE);
        }

        if($traverser->createBranch()->eatPattern('[' . $newlineRegexSet . ']')){
            return new BadStringToken($delimiter, $pieces);
        }

        $stringPiece = $traverser->eatPattern(
            '[^' . $newlineRegexSet . $delimiter . '\\\\]+'
        );
        if(isset($stringPiece)){
            $pieces[] = new StringBitToken($stringPiece);
            continue;
        }

        $escape = $eatEscapeToken($traverser);
        if(isset($escape)){
            $pieces[] = $escape;
            continue;
        }

        assert(FALSE);
    }
}
