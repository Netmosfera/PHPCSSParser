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
    Closure $eatEscapeToken
): ?AnyStringToken{
    $delimiter = $traverser->eatPattern('\'|"');

    if($delimiter === NULL){
        return NULL;
    }

    $eDelimiter = $traverser->escapeRegexp($delimiter);

    $pieces = [];

    for(;;){
        if($traverser->isEOF()){
            return new CheckedStringToken($delimiter, $pieces, TRUE);
        }

        if($traverser->eatString($delimiter) === $delimiter){
            return new CheckedStringToken($delimiter, $pieces, FALSE);
        }

        if($traverser->createBranch()->eatPattern('[' . $newlineRegexSet . ']')){
            return new CheckedBadStringToken($delimiter, $pieces);
        }

        $stringPiece = $traverser->eatPattern(
            '[^' . $newlineRegexSet . $eDelimiter . '\\\\]+'
        );
        if($stringPiece !== NULL){
            $pieces[] = new CheckedStringBitToken($stringPiece);
            continue;
        }

        $escape = $eatEscapeToken($traverser);
        if($escape !== NULL){
            $pieces[] = $escape;
            continue;
        }

        assert(FALSE);
    }
}
