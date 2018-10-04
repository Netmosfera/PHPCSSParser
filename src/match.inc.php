<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSAST;

use Generator;
use function is_object;

function match($a, $b){
    if($a === NULL || $b === NULL || is_scalar($a) || is_scalar($b)){
        return $a === $b;
    }

    if(gettype($a) !== gettype($b)){
        return FALSE;
    }

    if(is_object($a)){
        if($a === $b){
            return TRUE;
        }
        return $a->equals($b) && assert($b->equals($a));
    }

    if(count($a) !== count($b)){
        return FALSE;
    }

    if(array_keys($a) !== array_keys($b)){
        return FALSE;
    }

    $aIterator = (function() use($a){
        yield from $a;
    })();

    $bIterator = (function() use($b){
        yield from $b;
    })();

    /** @var Generator $aIterator */
    /** @var Generator $bIterator */

    $aIterator->rewind();
    $bIterator->rewind();

    while(TRUE){
        if($aIterator->valid() === FALSE){
            return TRUE;
        }

        if(match($aIterator->key(), $bIterator->key()) === FALSE){
            return FALSE;
        }

        if(match($aIterator->current(), $bIterator->current()) === FALSE){
            return FALSE;
        }

        $aIterator->next();
        $bIterator->next();
    }

    throw new \Error("should not happen");
}
