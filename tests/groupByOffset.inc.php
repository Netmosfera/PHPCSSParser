<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTTests;

function groupByOffset(array $a, array $b){
    $result = [];
    foreach($a as $i => $va){
        $result[] = [$a[$i], $b[$i]];
    }
    return $result;
}
