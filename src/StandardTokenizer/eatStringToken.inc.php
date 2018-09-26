<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\BadStringToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringBitToken;
use Netmosfera\PHPCSSAST\Tokens\Strings\StringToken;
use Closure;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Consumes a {@see StringToken}, if any.
 */
function eatStringToken(
    Traverser $traverser,
    String $newlineRegExpSet,
    Closure $eatEscape
): ?AnyStringToken{
    $delimiter = $traverser->eatExp('\'|"');

    if($delimiter === NULL){
        return NULL;
    }

    $eDelimiter = $traverser->escapeRegexp($delimiter);

    $pieces = [];

    for(;;){
        if($traverser->isEOF()){
            return new StringToken($delimiter, $pieces, TRUE);
        }

        if($traverser->eatStr($delimiter) === $delimiter){
            return new StringToken($delimiter, $pieces, FALSE);
        }

        if($traverser->createBranch()->eatExp('[' . $newlineRegExpSet . ']')){
            return new BadStringToken($delimiter, $pieces);
        }

        $stringPiece = $traverser->eatExp('[^' . $newlineRegExpSet . $eDelimiter . '\\\\]+');
        if($stringPiece !== NULL){
            $pieces[] = new StringBitToken($stringPiece);
            continue;
        }

        $escape = $eatEscape($traverser);
        if($escape !== NULL){
            $pieces[] = $escape;
        }
    }
}
