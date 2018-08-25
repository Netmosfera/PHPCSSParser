<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use ArrayIterator;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function match($a, $b){
    if($a === $b){
        return TRUE;
    }

    if(
        $a === NULL ||
        $b === NULL ||
        is_scalar($a) ||
        is_scalar($b)
    ){
        return $a === $b;
    }

    $aIsArray = is_array($a);
    $bIsArray = is_array($b);
    if($aIsArray && $bIsArray){
        if(count($a) !== count($b)){
            return FALSE;
        }

        if(array_keys($a) !== array_keys($b)){ return FALSE; }

        $aIterator = new ArrayIterator($a);
        $bIterator = new ArrayIterator($b);

        $aIterator->rewind();
        $bIterator->rewind();

        CHECK_ENTRY:

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

        goto CHECK_ENTRY;

    }elseif($aIsArray || $bIsArray){
        return FALSE;
    }

    return $a->equals($b);
}
