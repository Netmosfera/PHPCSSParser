<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;

function eatStringToken(
    Traverser $traverser,
    String $newlineRegexSet,
    Closure $eatEscapeToken,
    String $StringBitTokenClass = CheckedStringBitToken::CLASS,
    String $StringTokenClass = CheckedStringToken::CLASS,
    String $BadStringTokenClass = CheckedBadStringToken::CLASS
): ?AnyStringToken{
    $delimiter = $traverser->eatPattern('\'|"');

    if(isset($delimiter));else{
        return NULL;
    }

    $pieces = [];

    for(;;){
        if(isset($traverser->data[$traverser->index]));else{
            return new $StringTokenClass($delimiter, $pieces, TRUE);
        }

        if($traverser->eatString($delimiter) === $delimiter){
            return new $StringTokenClass($delimiter, $pieces, FALSE);
        }

        if($traverser->createBranch()->eatPattern('[' . $newlineRegexSet . ']')){
            return new $BadStringTokenClass($delimiter, $pieces);
        }

        $stringPiece = $traverser->eatPattern(
            '[^' . $newlineRegexSet . $delimiter . '\\\\]+'
        );
        if(isset($stringPiece)){
            $pieces[] = new $StringBitTokenClass($stringPiece);
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
