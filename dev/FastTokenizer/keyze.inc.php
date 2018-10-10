<?php declare(strict_types = 1);

namespace Netmosfera\PHPCSSASTDev\FastTokenizer;

function keyze(array $array){
    return array_combine(
        array_values($array),
        array_fill(0, count($array), TRUE)
    );
}
