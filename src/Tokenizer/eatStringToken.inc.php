<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST\Tokenizer;

use Closure;
use Netmosfera\PHPCSSAST\SpecData;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;

function eatStringToken(
    Traverser $traverser,
    String $newlineRegexSet = SpecData::NEWLINES_REGEX_SET,
    ?Closure $eatEscapeToken = NULL,
    String $StringBitTokenClass = StringBitToken::CLASS,
    String $StringTokenClass = StringToken::CLASS,
    String $BadStringTokenClass = BadStringToken::CLASS
): ?AnyStringToken{
    if(isset($eatEscapeToken));else{
        $eatEscapeToken = __NAMESPACE__ . "\\eatEscapeToken";
    }

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
