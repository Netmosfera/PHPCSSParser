<?php declare(strict_types = 1); // atom

namespace Netmosfera\PHPCSSASTDev;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function iterator_to_array;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function cartesianProduct(Iterable $array, Iterable ...$arrays){
    $cartesian = function(Array $rows, Array $arrays) use(&$cartesian){
        if($arrays === []){
            return $rows;
        }

        $newRows = [];
        $items = array_shift($arrays);
        foreach($rows as $row){
            foreach($items as $item){
                $newRows[] = array_merge($row, [$item]);
            }
        }
        return $cartesian($newRows, $arrays);
    };

    $allArrays = [is_array($array) ? $array : iterator_to_array($array, FALSE)];
    foreach($arrays as $x){
        $allArrays[] = is_array($x) ? $x : iterator_to_array($x, FALSE);
    }

    return $cartesian([[]], $allArrays);
}
