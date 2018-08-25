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

        reset($a);
        reset($b);

        CHECK_ENTRY:

        if(key($a) === NULL){
            return TRUE;
        }

        if(match(key($a), key($b)) === FALSE){
            return FALSE;
        }

        if(match(current($a), current($b)) === FALSE){
            return FALSE;
        }

        next($a);
        next($b);

        goto CHECK_ENTRY;

    }elseif($aIsArray || $bIsArray){
        return FALSE;
    }

    return $a->equals($b);
}
