<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST\StandardTokenizer;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\PHPCSSAST\Tokens\Strings\AnyStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedBadStringToken;
use Netmosfera\PHPCSSAST\TokensChecked\Strings\CheckedStringBitToken;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

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
            return new CheckedStringToken($delimiter, $pieces, TRUE);
        }

        if($traverser->eatStr($delimiter) === $delimiter){
            return new CheckedStringToken($delimiter, $pieces, FALSE);
        }

        if($traverser->createBranch()->eatExp('[' . $newlineRegExpSet . ']')){
            return new CheckedBadStringToken($delimiter, $pieces);
        }

        $stringPiece = $traverser->eatExp('[^' . $newlineRegExpSet . $eDelimiter . '\\\\]+');
        if($stringPiece !== NULL){
            $pieces[] = new CheckedStringBitToken($stringPiece);
            continue;
        }

        $escape = $eatEscape($traverser);
        if($escape !== NULL){
            $pieces[] = $escape;
        }
    }
}
