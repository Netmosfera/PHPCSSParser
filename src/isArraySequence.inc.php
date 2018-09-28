<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSAST;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function isArraySequence(Array $array): Bool{
    if(array_values($array) !== $array){
        return FALSE;
    }

    $array[] = 'test next index';

    end($array);
    $lastIndex = key($array);

    return $lastIndex + 1 === count($array);
}
