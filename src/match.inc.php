<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use ArrayIterator;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function match($a, $b){
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
    if($aIsArray || $bIsArray){
        if($aIsArray && $bIsArray){
            if(count($a) !== count($b)){
                return FALSE;
            }

            $aKeys = array_keys($a);
            $bKeys = array_keys($b);
            foreach($aKeys as $o => $_){
                $aKey = $aKeys[$o];
                $bKey = $bKeys[$o];
                if(match($aKey, $bKey) === FALSE){
                    return FALSE;
                }
            }

            $aValues = array_values($a);
            $bValues = array_values($b);
            foreach($aValues as $o => $_){
                $aValue = $aValues[$o];
                $bValue = $bValues[$o];
                if(match($aValue, $bValue) === FALSE){
                    return FALSE;
                }
            }

            return TRUE;
        }
        return FALSE;
    }

    return $a->equals($b);
}
